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
            return redirect()->route('client.dashboard');
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
            $user = Auth::user();
            
            Log::info('Login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'is_admin' => $user->canAccessAdmin()
            ]);
            
            // Get the intended URL before we potentially modify it
            $intendedUrl = $request->session()->get('url.intended');
            
            // Admin users
            if ($user->canAccessAdmin()) {
                // Allow admin to access any intended URL
                return redirect()->intended('/admin');
            }
            
            // Regular customers
            // If intended URL is an admin route, clear it and redirect to client dashboard
            if ($intendedUrl && str_starts_with($intendedUrl, url('/admin'))) {
                $request->session()->forget('url.intended');
                Log::info('Cleared admin intended URL for customer', [
                    'user_id' => $user->id,
                    'intended_url' => $intendedUrl
                ]);
            }
            
            return redirect()->intended(route('client.dashboard'));
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
