<?php

namespace Rawilk\LaravelBase\Contracts\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;

interface ImpersonatesUsers
{
    /**
     * Start impersonating a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return bool
     */
    public function impersonate(Request $request, StatefulGuard $guard, Authenticatable $user): bool;

    /**
     * Stop impersonating the currently impersonated user and revert to the original session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @param  string<class-string>  $userModel
     * @return bool
     */
    public function stopImpersonating(Request $request, StatefulGuard $guard, string $userModel): bool;

    /**
     * Determine if a user is currently being impersonated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function impersonating(Request $request): bool;

    /**
     * Return the impersonator's user id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function impersonatorId(Request $request);

    /**
     * Return the name of the current impersonator.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function impersonatorName(Request $request): ?string;

    /**
     * Remove any impersonation data from the session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function flushImpersonationData(Request $request): void;
}
