<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Events\Webauthn;

use Illuminate\Queue\SerializesModels;
use Rawilk\Webauthn\Contracts\WebauthnKey;

class WebauthnKeyWasUpdated
{
    use SerializesModels;

    public function __construct(public WebauthnKey $webauthnKey)
    {
    }
}
