<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class AuthenticateCustomer extends Middleware
{

    public function handle($request, \Closure $next, ...$guards)
    {
        if (empty($guards)) {
            $guards = ['customer'];
        }

        return parent::handle($request, $next, ...$guards);
    }

    protected function redirectTo($request): ?string
    {
        if (!$request->expectsJson()) {
            return route('customer.login');
        }
        return null;
    }

}
