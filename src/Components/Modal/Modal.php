<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Modal;

use Rawilk\LaravelBase\Components\BladeComponent;

class Modal extends BladeComponent
{
    public function __construct(
        public null|string $id = null,
        public null|string $maxWidth = null,
        public bool $showClose = true,
    ) {
    }

    public function id(): null|string
    {
        return $this->id ?? md5((string) $this->attributes->wire('model'));
    }

    public function maxWidth(): string
    {
        return match ($this->maxWidth ?? '2xl') {
            'sm' => 'sm:max-w-sm',
            'md' => 'sm:max-w-md',
            'lg' => 'sm:max-w-lg',
            'xl' => 'sm:max-w-xl',
            default => 'sm:max-w-2xl',
        };
    }
}
