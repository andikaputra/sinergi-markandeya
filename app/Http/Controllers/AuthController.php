<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Services\AisService;

class AuthController extends Controller
{
    public function __construct(private AisService $ais) {}

    // Menampilkan form login
    public function showLoginForm()
    {
        return view('login');
    }

    // Proses login
    // ── LOGIN LAMA (local DB only, tanpa AIS) ────────────────────────────────
    // public function loginLama(Request $request)
    // {
    //     $request->validate(['email' => 'required', 'password' => 'required']);
    //     $loginValue = $request->email;
    //     $password   = $request->password;
    //     if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
    //         if (Auth::guard('mahasiswa')->attempt(['email' => $loginValue, 'password' => $password])) {
    //             $mhs = Auth::guard('mahasiswa')->user();
    //             if ($mhs->status === 'nonaktif') {
    //                 Auth::guard('mahasiswa')->logout();
    //                 $alasan = $mhs->catatan_penolakan ? ' Alasan: ' . $mhs->catatan_penolakan : '';
    //                 return back()->withErrors(['email' => 'Akun Anda dinonaktifkan oleh admin.' . $alasan]);
    //             }
    //             $request->session()->regenerate();
    //             return redirect()->route('dashboard')->with('success', 'Login berhasil!');
    //         }
    //         if (Auth::guard('pembimbing_luar')->attempt(['email' => $loginValue, 'password' => $password])) {
    //             $request->session()->regenerate();
    //             return redirect()->route('pembimbing_luar.dashboard')->with('success', 'Login Pembimbing Luar berhasil!');
    //         }
    //     } else {
    //         if (Auth::guard('mahasiswa')->attempt(['nim' => $loginValue, 'password' => $password])) {
    //             $mhs = Auth::guard('mahasiswa')->user();
    //             if ($mhs->status === 'nonaktif') {
    //                 Auth::guard('mahasiswa')->logout();
    //                 $alasan = $mhs->catatan_penolakan ? ' Alasan: ' . $mhs->catatan_penolakan : '';
    //                 return back()->withErrors(['email' => 'Akun Anda dinonaktifkan oleh admin.' . $alasan]);
    //             }
    //             $request->session()->regenerate();
    //             return redirect()->route('dashboard')->with('success', 'Login berhasil!');
    //         }
    //         if (Auth::guard('dosen')->attempt(['nidn' => $loginValue, 'password' => $password])) {
    //             $request->session()->regenerate();
    //             return redirect()->route('dosen.dashboard')->with('success', 'Login Dosen berhasil!');
    //         }
    //     }
    //     return back()->withErrors(['email' => 'Kredensial tidak ditemukan!']);
    // }
    // ────────────────────────────────────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $loginValue = $request->email;
        $password = $request->password;

        if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
            // Email login: mahasiswa or pembimbing_luar (local only)
            if (Auth::guard('mahasiswa')->attempt(['email' => $loginValue, 'password' => $password])) {
                $mhs = Auth::guard('mahasiswa')->user();
                if ($mhs->status === 'nonaktif') {
                    Auth::guard('mahasiswa')->logout();
                    $alasan = $mhs->catatan_penolakan ? ' Alasan: ' . $mhs->catatan_penolakan : '';
                    return back()->withErrors(['email' => 'Akun Anda dinonaktifkan oleh admin.' . $alasan]);
                }
                $request->session()->regenerate();
                return redirect()->route('dashboard')->with('success', 'Login berhasil!');
            }

            if (Auth::guard('pembimbing_luar')->attempt(['email' => $loginValue, 'password' => $password])) {
                $request->session()->regenerate();
                return redirect()->route('pembimbing_luar.dashboard')->with('success', 'Login Pembimbing Luar berhasil!');
            }
        } else {
            // NIM / NIDN login: try local first, call AIS only if local fails (password changed)

            // --- Mahasiswa ---
            if (Auth::guard('mahasiswa')->attempt(['nim' => $loginValue, 'password' => $password])) {
                $mhs = Auth::guard('mahasiswa')->user();
                if ($mhs->status === 'nonaktif') {
                    Auth::guard('mahasiswa')->logout();
                    $alasan = $mhs->catatan_penolakan ? ' Alasan: ' . $mhs->catatan_penolakan : '';
                    return back()->withErrors(['email' => 'Akun Anda dinonaktifkan oleh admin.' . $alasan]);
                }
                $request->session()->regenerate();
                return redirect()->route('dashboard')->with('success', 'Login berhasil!');
            }

            // Local mahasiswa auth failed → try AIS (password may have changed)
            $aisMhs = $this->ais->loginMahasiswa($loginValue, $password);
            if ($aisMhs) {
                $mhs = $this->ais->syncMahasiswa($aisMhs, $password);
                if ($mhs->status === 'nonaktif') {
                    $alasan = $mhs->catatan_penolakan ? ' Alasan: ' . $mhs->catatan_penolakan : '';
                    return back()->withErrors(['email' => 'Akun Anda dinonaktifkan oleh admin.' . $alasan]);
                }
                Auth::guard('mahasiswa')->login($mhs);
                $request->session()->regenerate();
                return redirect()->route('dashboard')->with('success', 'Login berhasil!');
            }

            // --- Dosen ---
            if (Auth::guard('dosen')->attempt(['nidn' => $loginValue, 'password' => $password])) {
                $request->session()->regenerate();
                return redirect()->route('dosen.dashboard')->with('success', 'Login Dosen berhasil!');
            }

            // Local dosen auth failed → try AIS (password may have changed)
            $aisDosen = $this->ais->loginDosen($loginValue, $password);
            if ($aisDosen) {
                $dosen = $this->ais->syncDosen($aisDosen, $password);
                Auth::guard('dosen')->login($dosen);
                $request->session()->regenerate();
                return redirect()->route('dosen.dashboard')->with('success', 'Login Dosen berhasil!');
            }
        }

        return back()->withErrors(['email' => 'Kredensial tidak ditemukan!']);
    }

    // Logout
    public function logout(Request $request)
    {
        if (Auth::guard('mahasiswa')->check()) {
            Auth::guard('mahasiswa')->logout();
        } elseif (Auth::guard('dosen')->check()) {
            Auth::guard('dosen')->logout();
        } elseif (Auth::guard('pembimbing_luar')->check()) {
            Auth::guard('pembimbing_luar')->logout();
        } else {
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah logout.');
    }



    // admin
    public function showLoginFormAdmin()
    {
        return view('admin.adminlogin');
    }

    public function loginAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        if (Auth::guard('web')->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->route('admindashboard')->with('success', 'Login berhasil!');
        }
    
        return back()->withErrors(['email' => 'Email atau password salah!']);
    }

    public function logoutadmin(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin')->with('success', 'Anda telah logout.');
    }

    public function showLupaPasswordForm()
    {
        return view('auth.lupa-password');
    }

    public function lupaPassword(Request $request)
    {
        $request->validate([
            'nim'   => 'required|string',
            'email' => 'required|email',
        ]);

        $mahasiswa = Mahasiswa::where('nim', $request->nim)
            ->where('email', $request->email)
            ->first();

        if (!$mahasiswa) {
            return back()->withErrors(['nim' => 'NIM dan email tidak cocok. Periksa kembali data Anda.']);
        }

        if ($mahasiswa->status !== 'aktif') {
            return back()->withErrors(['nim' => 'Akun ini belum aktif. Hubungi admin.']);
        }

        $token = Str::random(64);
        $mahasiswa->update([
            'reset_token'            => hash('sha256', $token),
            'reset_token_expires_at' => now()->addHours(2),
        ]);

        return view('auth.lupa-password-token', compact('token', 'mahasiswa'));
    }

    public function showResetPasswordForm($token)
    {
        $hashed = hash('sha256', $token);
        $valid = Mahasiswa::where('reset_token', $hashed)
            ->where('reset_token_expires_at', '>', now())
            ->exists();

        if (!$valid) {
            return redirect()->route('lupa-password')->with('error', 'Link reset tidak valid atau sudah kadaluarsa. Minta ulang link baru.');
        }

        return view('auth.reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'password'              => 'required|min:8|confirmed',
        ]);

        $hashed = hash('sha256', $request->token);
        $mahasiswa = Mahasiswa::where('reset_token', $hashed)
            ->where('reset_token_expires_at', '>', now())
            ->first();

        if (!$mahasiswa) {
            return back()->withErrors(['token' => 'Link reset tidak valid atau sudah kadaluarsa.']);
        }

        $mahasiswa->update([
            'password'               => $request->password,
            'reset_token'            => null,
            'reset_token_expires_at' => null,
        ]);

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru.');
    }

    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = null;
        if (Auth::guard('mahasiswa')->check()) {
            $user = Auth::guard('mahasiswa')->user();
        } elseif (Auth::guard('dosen')->check()) {
            $user = Auth::guard('dosen')->user();
        } elseif (Auth::guard('pembimbing_luar')->check()) {
            $user = Auth::guard('pembimbing_luar')->user();
        } else {
            $user = Auth::user();
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak cocok!']);
        }

        $user->update([
            'password' => $request->new_password // hashed by model cast
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}
