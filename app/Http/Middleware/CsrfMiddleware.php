<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Access\AuthorizationException;
use Closure;
use Illuminate\Http\Request;

class CsrfMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next)
    {
        if (
            $request->header('csrf-token') !==
            auth()->payload()->get('csrf-token')
        ) {
            throw new AuthorizationException;
        }
        return $next($request);
    }
}
