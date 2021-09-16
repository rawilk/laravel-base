<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Elements;

use Rawilk\LaravelBase\Components\BladeComponent;

class Card extends BladeComponent
{
    // Types (for header styling)
    public const ERROR = 'error';
    public const SUCCESS = 'success';

    public function __construct(
        public bool $flush = false, // Set to true to remove padding from the body
        public $header = false,
        public $footer = false,
        public string $type = '',
    ) {
    }

    public function headerColorClasses(): string
    {
        return match ($this->type) {
            static::ERROR => 'bg-red-300 text-red-800',
            static::SUCCESS => 'bg-green-300 text-green-800',
            default => 'bg-blue-gray-50',
        };
    }
}
