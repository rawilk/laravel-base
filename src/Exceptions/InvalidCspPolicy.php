<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Exceptions;

use Exception;
use Rawilk\LaravelBase\Csp\Policies\CspPolicy;

class InvalidCspPolicy extends Exception
{
    public static function create(object $class): self
    {
        $className = $class::class;

        return new self("The CSP class `{$className}` is not valid. A valid policy extends " . CspPolicy::class);
    }
}
