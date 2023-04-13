<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Exceptions;

use Exception;

class InvalidCspDirective extends Exception
{
    public static function notSupported(string $directive): self
    {
        return new self("The directive `{$directive}` is not valid in a CSP header.");
    }
}
