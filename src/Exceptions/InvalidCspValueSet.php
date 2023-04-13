<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Exceptions;

use Exception;

class InvalidCspValueSet extends Exception
{
    public static function noneMustBeOnlyValue(): self
    {
        return new self('The keyword `none` can only be used on its own');
    }
}
