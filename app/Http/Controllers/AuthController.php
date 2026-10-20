<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function form()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required|string']);
        if (! Auth::attempt([...$credentials, 'active' => true])) {
            throw ValidationException::withMessages(['email' => 'Los datos de acceso no son válidos.']);
        }
        $request->session()->regenerate();
        if (! in_array($request->user()->role?->name, ['administrador', 'editor'], true)) {
            Auth::logout();
            abort(403);
        }

        return redirect()->intended('/admin');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
