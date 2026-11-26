<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $fieldType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$fieldType => $credentials['login'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            $hour = (int) now()->format('H');
            $waktu = match (true) {
                $hour >= 4 && $hour < 11  => 'Selamat Pagi',
                $hour >= 11 && $hour < 15 => 'Selamat Siang',
                $hour >= 15 && $hour < 18 => 'Selamat Sore',
                default                   => 'Selamat Malam',
            };

            $pesanPeran = $user->isPemilik()
                ? 'Anda masuk sebagai Pemilik Usaha (Admin). Pantau harga beli TBS, pengiriman PKS, dan arus transaksi hari ini.'
                : 'Anda masuk sebagai Petugas Operasional. Selamat bertugas mencatat timbangan pasokan TBS petani dan pengiriman TBS ke PKS.';

            return redirect()->intended(route('dashboard'))
                ->with('login_welcome', "{$waktu} & Selamat Datang Kembali, {$user->name}! {$pesanPeran}");
        }

        return back()->withErrors([
            'login' => 'Username/Email atau password yang dimasukkan tidak sesuai.',
        ])->onlyInput('login');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
