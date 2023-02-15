<?php

namespace Rawilk\LaravelBase\Contracts\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;

interface ImpersonatesUsers
{
    /**
     * Start impersonating a user.
     */
    public function impersonate(Request $request, StatefulGuard $guard, Authenticatable $user): bool;

    /**
     * Stop impersonating the currently impersonated user and revert to the original session.
     *
     * @param  string<class-string>  $userModel
     */
    public function stopImpersonating(Request $request, StatefulGuard $guard, string $userModel): bool;

    /**
     * Determine if a user is currently being impersonated.
     */
    public function impersonating(Request $request): bool;

    /**
     * Return the impersonator's user id.
     *
     * @return mixed
     */
    public function impersonatorId(Request $request);

    /**
     * Return the name of the current impersonator.
     */
    public function impersonatorName(Request $request): ?string;

    /**
     * Remove any impersonation data from the session.
     */
    public function flushImpersonationData(Request $request): void;
}
