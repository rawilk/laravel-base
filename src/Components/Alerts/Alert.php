<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Alerts;

use Illuminate\Support\Arr;
use Rawilk\LaravelBase\Components\BladeComponent;

class Alert extends BladeComponent
{
    // Available types
    public const INFO = 'info';
    public const SUCCESS = 'success';
    public const ERROR = 'error';
    public const WARNING = 'warning';

    public function __construct(
        public null | string $type = null,
        public bool | string $icon = true,
        public bool $dismiss = false,
        public bool $border = true,
        public null | string $title = '',
    ) {
        if (is_null($type)) {
            $this->type = static::INFO;
        }
    }

    public function alertClass(): string
    {
        return Arr::toCssClasses([
            'alert',
            'rounded-md p-4',
            "alert--{$this->type}",
            'border-l-4 rounded-none' => $this->border,
        ]);
    }

    public function iconComponent(): string
    {
        if (is_string($this->icon)) {
            return $this->icon;
        }

        return match ($this->type) {
            static::ERROR => 'heroicon-s-x-circle',
            static::SUCCESS => 'heroicon-s-check-circle',
            static::WARNING => 'heroicon-s-exclamation',
            default => 'heroicon-s-information-circle',
        };
    }
}
