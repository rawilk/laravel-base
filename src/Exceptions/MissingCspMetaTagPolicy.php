<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Exceptions;

use Exception;

class MissingCspMetaTagPolicy extends Exception
{
    public static function create(): self
    {
        return new self('The [@cspMetaTag] directive expects to be passed a valid policy class name');
    }
}
