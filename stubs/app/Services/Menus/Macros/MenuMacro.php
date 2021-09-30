<?php

declare(strict_types=1);

namespace App\Services\Menus\Macros;

abstract class MenuMacro
{
    abstract public function register(): void;
}
