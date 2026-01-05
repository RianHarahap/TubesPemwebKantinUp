<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request) {
        // Validasi input agar tidak kosong
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $data = [
            'name' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($data)) {
            $request->session()->regenerate();
            $role = Auth::user()->role;

            // Poin 3: Logika pengalihan (Redirect) berdasarkan Role
            if ($role == 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($role == 'penjual') {
                return redirect()->intended('/penjual/dashboard');
            } else {
                return redirect()->intended('/user/home');
            }
        }

        // Jika gagal login
        return back()->withErrors(['login' => 'Username atau Password salah!']);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}