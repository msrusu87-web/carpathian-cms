<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate as FilamentAuth;

class FilamentAuthenticate extends FilamentAuth
{
    protected function redirectTo($request): ?string
    {
        return route('login');
    }
}
