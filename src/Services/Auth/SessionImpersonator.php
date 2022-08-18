<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Services\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Rawilk\LaravelBase\Contracts\Models\ImpersonatesUsers;

class SessionImpersonator implements ImpersonatesUsers
{
    public function impersonate(Request $request, StatefulGuard $guard, Authenticatable $user): bool
    {
        return rescue(function () use ($request, $guard, $user) {
            $request->session()->put(
                $this->sessionKey(),
                $request->user()->getAuthIdentifier(),
            );
            $request->session()->put(
                $this->rememberSessionKey(),

                /*
                 * We are checking if there is a cookie created to remember login instead of
                 * $guard->viaRemember() for more reliability.
                 *
                 * See: https://github.com/laravel/framework/issues/19499#issuecomment-707822740
                 */
                Cookie::has($guard->getRecallerName()),
            );
            $request->session()->put(
                $this->nameSessionKey(),
                $request->user()->nameForImpersonation(),
            );

            $guard->quietLogin($user);

            /*
             * We are storing the impersonated user's session id in the session, so we can
             * remove that session from their account when we leave the impersonation.
             * This can help reduce confusion from seeing that session on their account
             * when the database session driver is used.
             */
            $request->session()->put(
                $this->sessionIdSessionKey(),
                $request->session()->getId(),
            );

            return true;
        }, false);
    }

    public function stopImpersonating(Request $request, StatefulGuard $guard, string $userModel): bool
    {
        return rescue(function () use ($request, $guard, $userModel) {
            $impersonatorId = $request->session()->get(
                $this->sessionKey(),
                null,
            );
            $impersonator = $userModel::withoutGlobalScopes()->findOrFail($impersonatorId);

            $sessionId = $request->session()->get($this->sessionIdSessionKey());
            $user = $request->user();

            $guard->quietLogin($impersonator, $request->session()->get($this->rememberSessionKey()) ?? false);

            $this->flushImpersonationData($request);

            $this->removeImpersonatedSession($sessionId, $user);

            return true;
        }, false);
    }

    public function impersonating(Request $request): bool
    {
        return $request->session()->has($this->sessionKey());
    }

    public function impersonatorId(Request $request)
    {
        return $request->session()->get($this->sessionKey());
    }

    public function impersonatorName(Request $request): ?string
    {
        return $request->session()->get($this->nameSessionKey());
    }

    public function flushImpersonationData(Request $request): void
    {
        if ($request->hasSession()) {
            $request->session()->forget($this->sessionKey());
            $request->session()->forget($this->rememberSessionKey());
            $request->session()->forget($this->nameSessionKey());
            $request->session()->forget($this->sessionIdSessionKey());
        }
    }

    protected function removeImpersonatedSession($sessionId, Authenticatable $user): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', $user->getAuthIdentifier())
            ->where('id', $sessionId)
            ->delete();
    }

    protected function nameSessionKey(): string
    {
        return config('laravel-base.impersonation.name_session_key', 'laravel_base.impersonated_name');
    }

    protected function rememberSessionKey(): string
    {
        return config('laravel-base.impersonation.remember_session_key', 'laravel_base.impersonated_remember');
    }

    protected function sessionKey(): string
    {
        return config('laravel-base.impersonation.session_key', 'laravel_base.impersonated_by');
    }

    protected function sessionIdSessionKey(): string
    {
        return config('laravel-base.impersonation.session_id_key', 'laravel_base.impersonated_session_id');
    }
}
