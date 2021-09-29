<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Responses\Auth;

use Rawilk\LaravelBase\Components\Alerts\Alert;
use Rawilk\LaravelBase\Contracts\Auth\PasswordResetResponse as PasswordResetResponseContract;
use Rawilk\LaravelBase\LaravelBase;

class PasswordResetResponse implements PasswordResetResponseContract
{
    /**
     * @param string $status The response status language key
     */
    public function __construct(protected string $status)
    {
    }

    public function toResponse($request)
    {
        return redirect(LaravelBase::redirects('password-reset', route('login')))
            ->with(Alert::SUCCESS, __($this->status));
    }
}
