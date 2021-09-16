<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Lists;

use Illuminate\Support\Arr;
use Rawilk\LaravelBase\Components\BladeComponent;

class InfoListItem extends BladeComponent
{
    public function __construct(public $label = '', public bool $dimmed = false)
    {
    }

    public function itemClasses(): string
    {
        return Arr::toCssClasses([
            'info-list-item',
            'odd:bg-white even:bg-blue-gray-50' => ! $this->dimmed,
            'dimmed' => $this->dimmed,
            'px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6',
            'opacity-50 bg-blue-gray-300 text-white pointer-events-none select-none' => $this->dimmed,
        ]);
    }

    public function labelClasses(): string
    {
        return Arr::toCssClasses([
            'text-sm leading-5 font-medium',
            'text-blue-gray-500' => $this->dimmed,
            'text-gray-500' => ! $this->dimmed,
        ]);
    }
}
