<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Csp\Nonce;

use Illuminate\Support\Facades\Vite;

class ViteNonceGenerator implements NonceGenerator
{
    public function generate(): string
    {
        return Vite::useCspNonce();
    }
}
