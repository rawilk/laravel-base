<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Rawilk\LaravelBase\Components\Alerts\Alert;
use Rawilk\LaravelBase\Contracts\Models\ImpersonatesUsers;
use Rawilk\LaravelBase\Events\Auth\UserWasImpersonated;

class ImpersonationController
{
    public function impersonate(Request $request, ImpersonatesUsers $impersonator)
    {
        $guard = config('laravel-base.guard') ?? config('auth.defaults.guard');
        $userModel = getModelForGuard($guard);

        $currentUser = $request->user();
        $user = $userModel::findOrFail($request->input('userId'));

        /*
         * If we're already impersonating someone, and we want to impersonate
         * someone else, then we'll first have to stop impersonating
         * and reload to 'refresh' back to the 'real' session.
         */
        if ($impersonator->impersonating($request)) {
            Session::flash(Alert::WARNING, __('base::users.impersonate.only_one_allowed_alert'));

            $impersonator->stopImpersonating(
                $request,
                Auth::guard($guard),
                $userModel,
            );

            return response()->json([
                'redirect' => route('admin.users.index'),
            ]);
        }

        /*
         * Now that we're guaranteed to be a 'real' user, we'll make sure we're
         * actually trying to impersonate someone besides ourselves, as that
         * would be unnecessary.
         */
        if (! $currentUser->is($user)) {
            abort_unless($currentUser?->canImpersonate() ?? false, Response::HTTP_FORBIDDEN, __('base::users.impersonate.cannot_impersonate_others'));
            abort_unless($user?->canBeImpersonated($currentUser) ?? false, Response::HTTP_FORBIDDEN, __('base::users.impersonate.cannot_impersonate_user'));

            $impersonator->impersonate(
                $request,
                Auth::guard($guard),
                $user,
            );

            UserWasImpersonated::dispatch($user, $currentUser);
        }

        if ($currentUser->is($user)) {
            Session::flash(Alert::ERROR, __('base::users.impersonate.cannot_impersonate_self'));
        }

        return response()->json([
            'redirect' => $this->getRedirect(),
        ]);
    }

    public function stopImpersonating(Request $request, ImpersonatesUsers $impersonator)
    {
        if ($impersonator->impersonating($request)) {
            $impersonator->stopImpersonating(
                $request,
                Auth::guard($guard = config('laravel-base.guard') ?? config('auth.defaults.guard')),
                getModelForGuard($guard),
            );
        }

        return response()->json([
            'redirect' => route('admin.users.index'),
        ]);
    }

    protected function getRedirect(): string
    {
        if (function_exists('homeRoute')) {
            return homeRoute();
        }

        return '/';
    }
}
