<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        $this->storeIntendedFromRequest($request);

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->storeIntendedFromRequest($request);

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

        event(new Registered($user));

        Auth::login($user);

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
}
