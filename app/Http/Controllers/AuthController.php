<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function login()
    {
        return view('auth.login');
    }

    // Proses login manual (tanpa hash)
    public function process(Request $request)
    {
        // Ambil user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Cek password secara manual (tanpa bcrypt)
        if ($user && $user->password === $request->password) {
            auth()->login($user); // login manual

            $request->session()->regenerate(); // hindari session fixation

            return redirect('/dashboard'); // arahkan ke halaman utama
        }

        // Jika gagal login
        return back()->with('error', 'Email atau password salah.');
    }

    // Logout user
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
