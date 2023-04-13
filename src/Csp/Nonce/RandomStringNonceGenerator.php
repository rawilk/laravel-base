<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Csp\Nonce;

use Illuminate\Support\Str;

class RandomStringNonceGenerator implements NonceGenerator
{
    public function generate(): string
    {
        return Str::random(32);
    }
}
