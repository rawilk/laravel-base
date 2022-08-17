<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Services\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
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
                $guard->viaRemember(),
            );
            $request->session()->put(
                $this->nameSessionKey(),
                $request->user()->nameForImpersonation(),
            );

            $guard->login($user);

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
            $impersonator = $userModel::findOrFail($impersonatorId);

            $guard->login($impersonator, $request->session()->get($this->rememberSessionKey()) ?? false);

            $this->flushImpersonationData($request);

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
        }
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
}
