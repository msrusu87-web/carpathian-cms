<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        // Support returning users to checkout (or any safe internal URL)
        // when they arrive on /register?redirect=/checkout
        $redirect = $request->query('redirect');
        if (is_string($redirect) && $redirect !== '') {
            $safe = $this->safeInternalRedirect($redirect);
            if ($safe) {
                $request->session()->put('url.intended', $safe);
            }
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign Client role to newly registered users
        $user->assignRole('Client');

        event(new Registered($user));

        Auth::login($user);

        // Redirect clients to client dashboard
        return redirect()->intended(route('client.dashboard', absolute: false));
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
}
