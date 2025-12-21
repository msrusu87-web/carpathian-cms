<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        // Support returning users to checkout (or any safe internal URL)
        // when they arrive on /login?redirect=/checkout
        $redirect = $request->query('redirect');
        if (is_string($redirect) && $redirect !== '') {
            $safe = $this->safeInternalRedirect($redirect);
            if ($safe) {
                $request->session()->put('url.intended', $safe);
            }
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    private function safeInternalRedirect(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        // Disallow protocol-relative redirects (//evil.com)
        if (Str::startsWith($value, '//')) {
            return null;
        }

        // Relative internal path
        if (Str::startsWith($value, '/')) {
            return $value;
        }

        // Absolute URL must match APP_URL host
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $appHost = parse_url(config('app.url'), PHP_URL_HOST);
            $valueHost = parse_url($value, PHP_URL_HOST);
            if ($appHost && $valueHost && Str::lower($appHost) === Str::lower($valueHost)) {
                $path = parse_url($value, PHP_URL_PATH) ?: '/';
                $query = parse_url($value, PHP_URL_QUERY);
                return $query ? ($path . '?' . $query) : $path;
            }
        }

        return null;
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
