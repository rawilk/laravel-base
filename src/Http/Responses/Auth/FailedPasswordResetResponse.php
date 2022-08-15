<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Responses\Auth;

use Illuminate\Validation\ValidationException;
use Rawilk\LaravelBase\Contracts\Auth\FailedPasswordResetResponse as FailedPasswordResetResponseContract;

class FailedPasswordResetResponse implements FailedPasswordResetResponseContract
{
    /**
     * @param  string  $status The response status language key
     */
    public function __construct(protected string $status)
    {
    }

    public function toResponse($request)
    {
        throw ValidationException::withMessages([
            'email' => [__($this->status)],
        ]);
    }
}
