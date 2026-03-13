<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Mahasiswa;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
    
        // Cek kredensial untuk mahasiswa (menggunakan email)
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            if (Auth::guard('mahasiswa')->attempt($request->only('email', 'password'))) {
                $request->session()->regenerate();
                return redirect()->route('dashboard')->with('success', 'Login berhasil!');
            }

            // Cek kredensial untuk pembimbing luar (menggunakan email)
            if (Auth::guard('pembimbing_luar')->attempt($request->only('email', 'password'))) {
                $request->session()->regenerate();
                return redirect()->route('pembimbing_luar.dashboard')->with('success', 'Login Pembimbing Luar berhasil!');
            }
        } else {
            // Cek kredensial untuk dosen (menggunakan nidn)
            if (Auth::guard('dosen')->attempt(['nidn' => $request->email, 'password' => $request->password])) {
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
    public function showLoginFormadmin()
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
