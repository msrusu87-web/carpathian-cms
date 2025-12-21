<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class SimpleLoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->canAccessAdmin()) {
                return redirect('/admin');
            }
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        Log::info('Login attempt', [
            'email' => $request->email,
            'ip' => $request->ip()
        ]);

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Session regeneration is handled automatically by Laravel's StartSession middleware
            
            $user = Auth::user();
            
            Log::info('Login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'is_admin' => $user->canAccessAdmin()
            ]);
            
            if ($user->canAccessAdmin()) {
                return redirect()->intended('/admin');
            }
            
            return redirect()->intended('/dashboard');
        }

        Log::warning('Login failed', ['email' => $request->email]);

        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
