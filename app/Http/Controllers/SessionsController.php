<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function index()
    {
        if(Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('sessions.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password', 'remember_me');

        if(Auth::attempt(['username' => $credentials['username'], 'password_laravel' => $credentials['password']], $credentials['remember_me'])) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'username' => 'Credenciales inválidas',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
