<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $adminUsername = config('gallery.admin_username');
        $adminPassword = config('gallery.admin_password');

        if ($credentials['username'] === $adminUsername && $credentials['password'] === $adminPassword) {
            session()->put('admin_authenticated', true);

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'password' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout()
    {
        session()->forget('admin_authenticated');

        return redirect()->route('admin.login');
    }
}
