<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Rawilk\LaravelBase\Exceptions\InactiveUserException;

class EnsureActiveUserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()) {
            return $next($request);
        }

        if (! method_exists($request->user(), 'isActive')) {
            return $next($request);
        }

        if (! $request->user()->isActive()) {
            throw new InactiveUserException;
        }

        return $next($request);
    }
}
