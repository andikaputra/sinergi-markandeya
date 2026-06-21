<?php

namespace App\Http\Controllers\Sso;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\SsoAuthCode;
use App\Models\SsoAccessToken;
use App\Models\SsoClient;
use App\Services\AisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SsoServerController extends Controller
{
    public function __construct(private AisService $ais) {}

    // ─────────────────────────────────────────────────────────────────────────
    // STEP 1 — Client redirects user here
    // GET /sso/authorize?client_id=&redirect_uri=&state=
    // ─────────────────────────────────────────────────────────────────────────
    public function authorize(Request $request)
    {
        $client = $this->resolveClient($request->client_id);
        if (!$client) {
            return response()->view('sso.error', ['message' => 'Aplikasi tidak terdaftar di SSO.'], 401);
        }

        if (!$client->allowsRedirect($request->redirect_uri)) {
            return response()->view('sso.error', ['message' => 'Redirect URI tidak diizinkan.'], 401);
        }

        // Check for existing SSO session (any guard)
        [$userType, $user] = $this->getActiveSsoUser();

        if ($user) {
            if (!$client->allowsRole($userType)) {
                return view('sso.login', [
                    'client'        => $client,
                    'redirect_uri'  => $request->redirect_uri,
                    'state'         => $request->state,
                    'allowed_roles' => $client->allowed_roles,
                    'error'         => 'Akun Anda (' . $userType . ') tidak memiliki akses ke ' . $client->name . '.',
                ]);
            }

            // Auto-issue code — true Single Sign-On
            $code = SsoAuthCode::generate($client->client_id, $userType, $user->id, $request->redirect_uri);
            return $this->redirectToClient($request->redirect_uri, $code->code, $request->state);
        }

        // Not authenticated — show login
        return view('sso.login', [
            'client'        => $client,
            'redirect_uri'  => $request->redirect_uri,
            'state'         => $request->state,
            'allowed_roles' => $client->allowed_roles,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STEP 2 — Process login form
    // POST /sso/authenticate
    // ─────────────────────────────────────────────────────────────────────────
    public function authenticate(Request $request)
    {
        $request->validate([
            'credential'   => 'required|string',
            'password'     => 'required|string',
            'redirect_uri' => 'required|url',
            'client_id'    => 'required|string',
            'state'        => 'nullable|string',
        ]);

        $client = $this->resolveClient($request->client_id);
        if (!$client || !$client->allowsRedirect($request->redirect_uri)) {
            return back()->withErrors(['credential' => 'Permintaan tidak valid.']);
        }

        $credential = trim($request->credential);
        $password   = $request->password;

        // --- Try Dosen ---
        if ($client->allowsRole('dosen')) {
            $dosen = $this->attemptDosen($credential, $password);
            if ($dosen) {
                Auth::guard('dosen')->login($dosen);
                $request->session()->regenerate();

                $code = SsoAuthCode::generate($client->client_id, 'dosen', $dosen->id, $request->redirect_uri);
                return $this->redirectToClient($request->redirect_uri, $code->code, $request->state);
            }
        }

        // --- Try Mahasiswa ---
        if ($client->allowsRole('mahasiswa')) {
            $mahasiswa = $this->attemptMahasiswa($credential, $password);
            if ($mahasiswa) {
                if ($mahasiswa->status === 'nonaktif') {
                    return back()->withErrors(['credential' => 'Akun Anda dinonaktifkan. Hubungi admin.'])
                        ->withInput(['credential' => $credential, 'client_id' => $request->client_id,
                            'redirect_uri' => $request->redirect_uri, 'state' => $request->state]);
                }
                Auth::guard('mahasiswa')->login($mahasiswa);
                $request->session()->regenerate();

                $code = SsoAuthCode::generate($client->client_id, 'mahasiswa', $mahasiswa->id, $request->redirect_uri);
                return $this->redirectToClient($request->redirect_uri, $code->code, $request->state);
            }
        }

        return back()
            ->withErrors(['credential' => 'Kredensial tidak ditemukan atau tidak memiliki akses ke ' . $client->name . '.'])
            ->withInput(['credential' => $credential, 'client_id' => $request->client_id,
                'redirect_uri' => $request->redirect_uri, 'state' => $request->state]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STEP 3 — Client exchanges code for token (server-to-server)
    // POST /api/sso/token
    // Body: { client_id, client_secret, code, redirect_uri }
    // ─────────────────────────────────────────────────────────────────────────
    public function token(Request $request)
    {
        $request->validate([
            'client_id'     => 'required|string',
            'client_secret' => 'required|string',
            'code'          => 'required|string',
            'redirect_uri'  => 'required|string',
        ]);

        $client = $this->resolveClient($request->client_id);
        if (!$client || !$client->verifySecret($request->client_secret)) {
            return response()->json(['error' => 'invalid_client'], 401);
        }

        $authCode = SsoAuthCode::where('code', $request->code)
            ->where('client_id', $client->client_id)
            ->where('redirect_uri', $request->redirect_uri)
            ->first();

        if (!$authCode || !$authCode->isValid()) {
            return response()->json(['error' => 'invalid_grant', 'message' => 'Kode tidak valid atau sudah kadaluarsa.'], 400);
        }

        $authCode->markUsed();

        [$user, $userData, $abilities] = $this->buildUserData($authCode->user_type, $authCode->user_id);
        if (!$user) {
            return response()->json(['error' => 'user_not_found'], 404);
        }

        $accessToken = SsoAccessToken::issue(
            $client->client_id,
            $authCode->user_type,
            $authCode->user_id,
            $userData,
            $abilities
        );

        return response()->json([
            'access_token' => $accessToken->token,
            'token_type'   => 'Bearer',
            'expires_in'   => $accessToken->expires_at->diffInSeconds(now()),
            'user_type'    => $authCode->user_type,
            'user'         => $userData,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STEP 4 — Client fetches user info
    // GET /api/sso/me  (Authorization: Bearer <token>)
    // ─────────────────────────────────────────────────────────────────────────
    public function me(Request $request)
    {
        $token = $this->extractBearerToken($request);
        if (!$token) {
            return response()->json(['error' => 'unauthenticated'], 401);
        }

        $accessToken = SsoAccessToken::where('token', $token)->first();
        if (!$accessToken || !$accessToken->isValid()) {
            return response()->json(['error' => 'token_invalid_or_expired'], 401);
        }

        $accessToken->touchLastUsed();

        // Return fresh user data
        [, $userData] = $this->buildUserData($accessToken->user_type, $accessToken->user_id);

        return response()->json([
            'user_type' => $accessToken->user_type,
            'abilities' => $accessToken->abilities,
            'user'      => $userData ?? $accessToken->user_data,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Logout — revoke token + clear SSO session
    // POST /api/sso/logout  (Authorization: Bearer <token>)
    // ─────────────────────────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        $token = $this->extractBearerToken($request);
        if ($token) {
            SsoAccessToken::where('token', $token)->first()?->revoke();
        }

        // Clear SSO session guards
        foreach (['dosen', 'mahasiswa', 'web'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout berhasil.']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────────────────

    private function resolveClient(?string $clientId): ?SsoClient
    {
        if (!$clientId) return null;
        return SsoClient::where('client_id', $clientId)->where('active', true)->first();
    }

    private function getActiveSsoUser(): array
    {
        if (Auth::guard('dosen')->check()) {
            return ['dosen', Auth::guard('dosen')->user()];
        }
        if (Auth::guard('mahasiswa')->check()) {
            return ['mahasiswa', Auth::guard('mahasiswa')->user()];
        }
        if (Auth::guard('web')->check()) {
            return ['admin', Auth::guard('web')->user()];
        }
        return [null, null];
    }

    private function attemptDosen(string $credential, string $password): ?Dosen
    {
        // Try local DB first (NIDN)
        if (Auth::guard('dosen')->attempt(['nidn' => $credential, 'password' => $password])) {
            $dosen = Auth::guard('dosen')->user();
            Auth::guard('dosen')->logout();
            return $dosen;
        }

        // Fallback to AIS
        $aisDosen = $this->ais->loginDosen($credential, $password);
        if ($aisDosen) {
            return $this->ais->syncDosen($aisDosen, $password);
        }

        return null;
    }

    private function attemptMahasiswa(string $credential, string $password): ?Mahasiswa
    {
        // Try NIM first
        $field = filter_var($credential, FILTER_VALIDATE_EMAIL) ? 'email' : 'nim';

        if (Auth::guard('mahasiswa')->attempt([$field => $credential, 'password' => $password])) {
            $mhs = Auth::guard('mahasiswa')->user();
            Auth::guard('mahasiswa')->logout();
            return $mhs;
        }

        // Fallback to AIS
        $aisMhs = $this->ais->loginMahasiswa($credential, $password);
        if ($aisMhs) {
            return $this->ais->syncMahasiswa($aisMhs, $password);
        }

        return null;
    }

    private function buildUserData(string $userType, int $userId): array
    {
        $abilities = [$userType];

        if ($userType === 'dosen') {
            $user = Dosen::find($userId);
            if (!$user) return [null, null, []];

            $userData = [
                'id'    => $user->id,
                'nidn'  => $user->nidn,
                'nip'   => $user->nip,
                'nama'  => $user->nama,
                'foto'  => $user->foto ? asset('storage/' . $user->foto) : null,
            ];
        } elseif ($userType === 'mahasiswa') {
            $user = Mahasiswa::find($userId);
            if (!$user) return [null, null, []];

            $userData = [
                'id'     => $user->id,
                'nim'    => $user->nim,
                'nama'   => $user->nama,
                'email'  => $user->email,
                'prodi'  => $user->prodi,
                'kampus' => $user->kampus,
                'foto'   => $user->foto ? asset('storage/' . $user->foto) : null,
                'status' => $user->status,
            ];
        } else {
            $user = \App\Models\User::find($userId);
            if (!$user) return [null, null, []];

            $userData = [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ];
            $abilities = ['admin'];
        }

        return [$user, $userData, $abilities];
    }

    private function redirectToClient(string $redirectUri, string $code, ?string $state): \Illuminate\Http\RedirectResponse
    {
        $url = $redirectUri . '?code=' . urlencode($code);
        if ($state) {
            $url .= '&state=' . urlencode($state);
        }
        return redirect($url);
    }

    private function extractBearerToken(Request $request): ?string
    {
        $header = $request->header('Authorization', '');
        if (str_starts_with($header, 'Bearer ')) {
            return substr($header, 7);
        }
        return null;
    }
}
