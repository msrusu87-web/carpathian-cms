<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate as FilamentAuth;
use Illuminate\Support\Facades\Log;

class FilamentAuthenticate extends FilamentAuth
{
    protected function redirectTo($request): ?string
    {
        // If user is authenticated but not an admin, redirect to customer dashboard
        if ($request->user() && !$request->user()->canAccessAdmin()) {
            Log::info('Non-admin user attempted to access admin panel', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email,
                'url' => $request->url()
            ]);
            
            // Don't redirect to login, redirect to customer dashboard instead
            return route('dashboard');
        }
        
        // For unauthenticated users, redirect to login
        return route('login');
    }
}
