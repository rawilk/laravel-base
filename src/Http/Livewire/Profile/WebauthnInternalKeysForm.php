<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Profile;

class WebauthnInternalKeysForm extends RegisterWebauthnKeyForm
{
    protected string $attachmentType = 'platform';
    protected string $viewName = 'livewire.profile.webauthn-internal-keys-form';

    public function boot(): void
    {
        $max = config('laravel-base.webauthn.internal_keys_per_user');

        $this->maxKeysAllowed = is_null($max) ? null : (int) $max;
    }
}
