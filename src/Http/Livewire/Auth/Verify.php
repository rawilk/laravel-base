<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Rawilk\LaravelBase\Contracts\Auth\VerifyEmailViewResponse;
use Rawilk\LaravelBase\LaravelBase;

class Verify extends Component
{
    public function resend()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->intended(LaravelBase::redirects('email-verification') . '?verified=1');
        }

        Auth::user()->sendEmailVerificationNotification();

        $this->emit('resent');

        Session::flash('resent');
    }

    public function render()
    {
        return app(VerifyEmailViewResponse::class)->toResponse(request());
    }
}
