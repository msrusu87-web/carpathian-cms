<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is not authenticated, let Filament's auth middleware handle it
        if (!Auth::check()) {
            return $next($request);
        }

        // If user is authenticated but NOT an admin, redirect to client dashboard
        if (!Auth::user()->canAccessAdmin()) {
            Log::info('Non-admin user blocked from admin panel', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'attempted_url' => $request->fullUrl()
            ]);
            
            // Clear any intended admin URLs
            $request->session()->forget('url.intended');
            
            // Redirect to client dashboard with a message
            return redirect()->route('client.dashboard')
                ->with('warning', __('messages.no_admin_access'));
        }

        return $next($request);
    }
}
