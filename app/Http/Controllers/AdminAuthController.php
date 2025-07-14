<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    // Menampilkan form login admin
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    // Proses login admin
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Cek apakah user adalah admin
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.products.index');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Anda bukan admin.']);
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    // Logout admin
    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login')->with('success', 'Berhasil logout.');
    }
}
