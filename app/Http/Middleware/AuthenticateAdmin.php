<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class AuthenticateAdmin extends Middleware
{

    protected function redirectTo($request): ?string
    {
        if (!$request->expectsJson()) {
            return route('admin.login');
        }
        return null;
    }

}
