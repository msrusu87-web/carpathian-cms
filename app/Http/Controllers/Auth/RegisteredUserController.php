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
    public function create(Request $request): View
    {
        $redirect = $request->query('redirect');
        if (is_string($redirect) && $redirect !== '') {
            $safe = $this->safeInternalRedirect($redirect);
            if ($safe) {
                $request->session()->put('url.intended', $safe);
            }
        }

        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:50'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_reg_number' => ['nullable', 'string', 'max:100'],
            'vat_number' => ['nullable', 'string', 'max:100'],
            'billing_address' => ['required', 'string', 'max:255'],
            'billing_city' => ['required', 'string', 'max:100'],
            'billing_postal_code' => ['required', 'string', 'max:20'],
            'billing_country' => ['required', 'string', 'max:100'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'company_reg_number' => $request->company_reg_number,
            'vat_number' => $request->vat_number,
            'billing_address' => $request->billing_address,
            'billing_city' => $request->billing_city,
            'billing_postal_code' => $request->billing_postal_code,
            'billing_country' => $request->billing_country,
        ]);

        // Assign Client role to newly registered users
        $user->assignRole('Client');

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended(route('client.dashboard', absolute: false));
    }

    private function safeInternalRedirect(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        if (Str::startsWith($value, '//')) {
            return null;
        }

        if (Str::startsWith($value, '/')) {
            return $value;
        }

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
