<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        // Validasi Input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check ingat saya
        $remember = $request->has('remember');

        // Attempt kredensial
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        // Fallback Error
        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak sesuai dengan catatan kami.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        // Logout
        Auth::logout();

        // Reset Session
        $request->session()->invalidate();

        // Regenerate Token
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
