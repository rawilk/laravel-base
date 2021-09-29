<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Rawilk\LaravelBase\LaravelBase;

class VerifyEmailController
{
    public function __invoke(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->intended(LaravelBase::redirects('email-verification') . '?verified=1');
    }
}
