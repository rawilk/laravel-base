<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Rawilk\LaravelBase\Components\Alerts\Alert;
use Rawilk\LaravelBase\Contracts\Models\ImpersonatesUsers;

class ProtectFromImpersonation
{
    public function __construct(protected ImpersonatesUsers $impersonator)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->impersonator->impersonating($request)) {
            return back()->with([
                Alert::ERROR => __('base::users.impersonate.route_protected'),
            ]);
        }

        return $next($request);
    }
}
