<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Actions\Auth;

use Illuminate\Validation\ValidationException;
use Rawilk\LaravelBase\LaravelBase;

class EnsureUserAccountIsActive
{
    public function __construct(protected LogoutAction $logoutAction)
    {
    }

    /**
     * Ensure the user's account is active.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        if (! method_exists($request->user(), 'isActive')) {
            return $next($request);
        }

        if (! $request->user()->isActive()) {
            return $this->throwFailedAuthenticationException($request);
        }

        return $next($request);
    }

    protected function throwFailedAuthenticationException($request): void
    {
        $key = $request->has('code')
            ? 'two_factor'
            : LaravelBase::username();

        $this->logoutAction->handle();

        throw ValidationException::withMessages([
            $key => [__('base::users.deactivated.message')],
        ]);
    }
}
