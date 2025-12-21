<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified.
     * Works for both authenticated and unauthenticated users.
     */
    public function __invoke(Request $request, $id, $hash): RedirectResponse
    {
        $user = User::find($id);
        
        if (!$user) {
            return redirect()->route('login')
                ->with('error', __('messages.user_not_found'));
        }
        
        // Verify the hash matches
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')
                ->with('error', __('messages.invalid_verification_link'));
        }
        
        // Check if already verified
        if ($user->hasVerifiedEmail()) {
            // Log in the user if not already logged in
            if (!Auth::check()) {
                Auth::login($user);
            }
            return redirect()->route('dashboard')
                ->with('success', __('messages.email_already_verified'));
        }
        
        // Mark email as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
        
        // Log in the user if not already logged in
        if (!Auth::check()) {
            Auth::login($user);
        }
        
        return redirect()->route('dashboard')
            ->with('success', __('messages.email_verified_successfully'));
    }
}
