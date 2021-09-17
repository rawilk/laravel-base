<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Feeds;

use Illuminate\Support\Arr;
use Rawilk\LaravelBase\Components\BladeComponent;

class Feed extends BladeComponent
{
    public function __construct(public bool $stacked = false)
    {
    }

    public function containerClass(): string
    {
        return Arr::toCssClasses([
            'flow-root' => ! $this->stacked,
        ]);
    }
}
