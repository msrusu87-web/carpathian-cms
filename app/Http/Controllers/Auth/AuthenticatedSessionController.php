<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        $this->storeIntendedFromRequest($request);

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $this->storeIntendedFromRequest($request);

        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    private function storeIntendedFromRequest(Request $request): void
    {
        $redirect = $request->input('redirect') ?? $request->query('redirect');
        if (!$redirect) {
            return;
        }

        $intended = $this->sanitizeRedirect($redirect);
        if ($intended) {
            $request->session()->put('url.intended', $intended);
        }
    }

    /**
     * Only allow redirects to the same host or relative paths.
     */
    private function sanitizeRedirect(string $redirect): ?string
    {
        $redirect = trim($redirect);
        if ($redirect === '') {
            return null;
        }

        // Relative path.
        if (str_starts_with($redirect, '/')) {
            return $redirect;
        }

        // Absolute URL: only allow same-host.
        $parts = parse_url($redirect);
        if (!$parts || empty($parts['host'])) {
            return null;
        }

        $currentHost = request()->getHost();
        if (!hash_equals($currentHost, $parts['host'])) {
            return null;
        }

        $path = $parts['path'] ?? '/';
        $query = isset($parts['query']) ? ('?' . $parts['query']) : '';

        return $path . $query;
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
