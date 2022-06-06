<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Profile;

class WebauthnSecurityKeysForm extends RegisterWebauthnKeyForm
{
    protected string $attachmentType = 'cross-platform';
    protected string $viewName = 'livewire.profile.webauthn-security-keys-form';

    public function boot(): void
    {
        $max = config('laravel-base.webauthn.max_security_keys_per_user', 5);

        $this->maxKeysAllowed = is_null($max) ? null : (int) $max;
    }
}
