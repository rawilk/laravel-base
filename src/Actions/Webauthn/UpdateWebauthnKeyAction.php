<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Actions\Webauthn;

use Illuminate\Support\Facades\Validator;
use Rawilk\LaravelBase\Events\Webauthn\WebauthnKeyWasUpdated;
use Rawilk\Webauthn\Contracts\WebauthnKey;

class UpdateWebauthnKeyAction
{
    public function __invoke(WebauthnKey $webauthnKey, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validate();

        $webauthnKey->forceFill([
            'name' => $input['name'],
        ])->save();

        if ($webauthnKey->wasChanged()) {
            event(new WebauthnKeyWasUpdated($webauthnKey));
        }
    }
}
