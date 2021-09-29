<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Inputs;

use Rawilk\LaravelBase\Components\BladeComponent;

class Otp extends BladeComponent
{
    public function __construct(
        public int $length = 6,
        public string $name = 'code',
        public bool $focus = true, // Set to true to autofocus first input
        public $value = '', // Does not apply when using wire:model
    ) {
    }

    public function gridCols(): string
    {
        return match ($this->length) {
            2 => 'grid-cols-2',
            3 => 'grid-cols-3',
            4 => 'grid-cols-4',
            5 => 'grid-cols-5',
            6 => 'grid-cols-6',
            7 => 'grid-cols-7',
            8 => 'grid-cols-8',
            9 => 'grid-cols-9',
            10 => 'grid-cols-10',
            11 => 'grid-cols-11',
            12 => 'grid-cols-12',
            default => 'grid-cols-1',
        };
    }
}
