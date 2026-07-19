<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\SsoAccessToken;
use App\Services\AisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * API v1 Auth — untuk mobile app dan integrasi langsung
 * (berbeda dari SSO web flow yang pakai auth code)
 */
class AuthController extends Controller
{
    public function __construct(private AisService $ais) {}

    // POST /api/v1/login — auto-detect dosen atau mahasiswa
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = trim($request->username);
        $password = $request->password;

        // Try dosen first (NIDN format: 10 digit)
        $dosen = $this->loginDosen($username, $password);
        if ($dosen) {
            return $this->dosenResponse($dosen);
        }

        // Try mahasiswa
        $mahasiswa = $this->loginMahasiswa($username, $password);
        if ($mahasiswa) {
            if ($mahasiswa->status === 'nonaktif') {
                return response()->json(['message' => 'Akun nonaktif. Hubungi admin.'], 403);
            }
            return $this->mahasiswaResponse($mahasiswa);
        }

        return response()->json(['message' => 'Kredensial tidak valid.'], 401);
    }

    // POST /api/v1/login/dosen
    public function loginDosenEndpoint(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $dosen = $this->loginDosen(trim($request->username), $request->password);
        if (!$dosen) {
            return response()->json(['message' => 'NIDN atau password salah.'], 401);
        }

        return $this->dosenResponse($dosen);
    }

    // POST /api/v1/login/mahasiswa
    public function loginMahasiswaEndpoint(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $mahasiswa = $this->loginMahasiswa(trim($request->username), $request->password);
        if (!$mahasiswa) {
            return response()->json(['message' => 'NIM/email atau password salah.'], 401);
        }
        if ($mahasiswa->status === 'nonaktif') {
            return response()->json(['message' => 'Akun nonaktif. Hubungi admin.'], 403);
        }

        return $this->mahasiswaResponse($mahasiswa);
    }

    // GET /api/v1/me  (Authorization: Bearer <token>)
    public function me(Request $request)
    {
        $token = $this->bearerToken($request);
        if (!$token) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $accessToken = SsoAccessToken::where('token', $token)
            ->where('client_id', 'api-v1')
            ->first();

        if (!$accessToken || !$accessToken->isValid()) {
            return response()->json(['message' => 'Token tidak valid atau kadaluarsa.'], 401);
        }

        $accessToken->touchLastUsed();

        return response()->json([
            'user_type' => $accessToken->user_type,
            'user'      => $accessToken->user_data,
        ]);
    }

    // POST /api/v1/logout  (Authorization: Bearer <token>)
    public function logout(Request $request)
    {
        $token = $this->bearerToken($request);
        if ($token) {
            SsoAccessToken::where('token', $token)->where('client_id', 'api-v1')->first()?->revoke();
        }
        return response()->json(['message' => 'Logout berhasil.']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    private function loginDosen(string $credential, string $password): ?Dosen
    {
        if (Auth::guard('dosen')->attempt(['nidn' => $credential, 'password' => $password])) {
            $dosen = Auth::guard('dosen')->user();
            Auth::guard('dosen')->logout();
            return $dosen;
        }

        $aisData = $this->ais->loginDosen($credential, $password);
        if ($aisData) {
            return $this->ais->syncDosen($aisData, $password);
        }

        return null;
    }

    private function loginMahasiswa(string $credential, string $password): ?Mahasiswa
    {
        $field = filter_var($credential, FILTER_VALIDATE_EMAIL) ? 'email' : 'nim';

        if (Auth::guard('mahasiswa')->attempt([$field => $credential, 'password' => $password])) {
            $mhs = Auth::guard('mahasiswa')->user();
            Auth::guard('mahasiswa')->logout();
            return $mhs;
        }

        $aisData = $this->ais->loginMahasiswa($credential, $password);
        if ($aisData) {
            return $this->ais->syncMahasiswa($aisData, $password);
        }

        return null;
    }

    private function dosenResponse(Dosen $dosen): \Illuminate\Http\JsonResponse
    {
        $userData = [
            'id'   => $dosen->id,
            'nidn' => $dosen->nidn,
            'nip'  => $dosen->nip,
            'nama' => $dosen->nama,
            'foto' => $dosen->foto ? asset('storage/' . $dosen->foto) : null,
        ];

        $token = SsoAccessToken::issue('api-v1', 'dosen', $dosen->id, $userData, ['dosen']);

        return response()->json([
            'access_token' => $token->token,
            'token_type'   => 'Bearer',
            'expires_in'   => $token->expires_at->diffInSeconds(now()),
            'user_type'    => 'dosen',
            'user'         => $userData,
        ]);
    }

    private function mahasiswaResponse(Mahasiswa $mahasiswa): \Illuminate\Http\JsonResponse
    {
        $userData = [
            'id'     => $mahasiswa->id,
            'nim'    => $mahasiswa->nim,
            'nama'   => $mahasiswa->nama,
            'email'  => $mahasiswa->email,
            'prodi'  => $mahasiswa->prodi,
            'kampus' => $mahasiswa->kampus,
            'foto'   => $mahasiswa->foto ? asset('storage/' . $mahasiswa->foto) : null,
            'status' => $mahasiswa->status,
        ];

        $token = SsoAccessToken::issue('api-v1', 'mahasiswa', $mahasiswa->id, $userData, ['mahasiswa']);

        return response()->json([
            'access_token' => $token->token,
            'token_type'   => 'Bearer',
            'expires_in'   => $token->expires_at->diffInSeconds(now()),
            'user_type'    => 'mahasiswa',
            'user'         => $userData,
        ]);
    }

    private function bearerToken(Request $request): ?string
    {
        $header = $request->header('Authorization', '');
        return str_starts_with($header, 'Bearer ') ? substr($header, 7) : null;
    }
}
