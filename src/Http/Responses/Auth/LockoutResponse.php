<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Responses\Auth;

use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Rawilk\LaravelBase\Contracts\Auth\LockoutResponse as LockoutResponseContract;
use Rawilk\LaravelBase\LaravelBase;
use Rawilk\LaravelBase\Support\Auth\LoginRateLimiter;

class LockoutResponse implements LockoutResponseContract
{
    public function __construct(protected LoginRateLimiter $limiter)
    {
    }

    public function toResponse($request)
    {
        return with($this->limiter->availableIn($request), function ($seconds) {
            throw ValidationException::withMessages([
                LaravelBase::username() => [
                    __('auth.throttle', [
                        'seconds' => $seconds,
                        'minutes' => ceil($seconds / 60),
                    ])
                ]
            ])->status(Response::HTTP_TOO_MANY_REQUESTS);
        });
    }
}
