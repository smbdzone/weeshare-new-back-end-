<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;


// use Cache; 
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Contracts\Auth\Authenticatable;
// use App\Http\Requests\ValidateSecretRequest;


class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
