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
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        // Cek kredensial untuk mahasiswa
        if (Auth::guard('mahasiswa')->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Login berhasil!');
        }
    
        return back()->withErrors(['email' => 'Email atau password salah!']);
    }

    

    // Logout
    public function logout()
    {
        Auth::logout();
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

    public function logoutadmin()
    {
        Auth::logout();
        return redirect('/admin')->with('success', 'Anda telah logout.');
    }
}
