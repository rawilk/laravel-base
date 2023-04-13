<?php

namespace Rawilk\LaravelBase\Csp\Nonce;

interface NonceGenerator
{
    public function generate(): string;
}
