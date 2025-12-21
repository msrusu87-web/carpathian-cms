<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/admin');
        }
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        Log::info('Admin login attempt', [
            'email' => $request->email,
            'ip' => $request->ip()
        ]);

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            Log::info('Admin login successful', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            // Check if user can access admin
            if ($user->canAccessAdmin()) {
                return redirect()->intended('/admin');
            }
            
            // Not an admin, logout and show error
            Auth::logout();
            return back()->withErrors([
                'email' => 'Nu aveți permisiunea să accesați panoul de administrare.',
            ])->withInput($request->only('email'));
        }

        Log::warning('Admin login failed', ['email' => $request->email]);

        return back()->withErrors([
            'email' => 'Email sau parolă incorectă.',
        ])->withInput($request->only('email'));
    }
}
