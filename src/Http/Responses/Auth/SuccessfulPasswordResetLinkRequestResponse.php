<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Responses\Auth;

use Illuminate\Support\Facades\Session;
use Rawilk\LaravelBase\Components\Alerts\Alert;
use Rawilk\LaravelBase\Contracts\Auth\SuccessfulPasswordResetLinkRequestResponse as SuccessfulPasswordResetLinkRequestResponseContract;

class SuccessfulPasswordResetLinkRequestResponse implements SuccessfulPasswordResetLinkRequestResponseContract
{
    /**
     * @param  string  $status The response status language key
     */
    public function __construct(protected string $status)
    {
    }

    /**
     * @psalm-suppress InvalidReturnType
     */
    public function toResponse($request)
    {
        Session::flash(Alert::SUCCESS, __($this->status));
    }
}
