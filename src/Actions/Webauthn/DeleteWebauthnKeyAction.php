<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Actions\Webauthn;

use Rawilk\LaravelBase\Events\Webauthn\WebauthnKeyWasDeleted;
use Rawilk\Webauthn\Contracts\WebauthnKey;

class DeleteWebauthnKeyAction
{
    public function __invoke(WebauthnKey $webauthnKey): void
    {
        $webauthnKey->delete();

        event(new WebauthnKeyWasDeleted($webauthnKey));
    }
}
