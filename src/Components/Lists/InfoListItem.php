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
            'odd:bg-white even:bg-slate-50 dark:even:bg-slate-900 dark:odd:bg-slate-800' => ! $this->dimmed,
            'dimmed' => $this->dimmed,
            'px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6',
            'opacity-50 bg-slate-300 dark:bg-slate-600 text-white pointer-events-none select-none' => $this->dimmed,
        ]);
    }

    public function labelClasses(): string
    {
        return Arr::toCssClasses([
            'text-sm leading-5 font-medium',
            'text-slate-500 dark:text-slate-200' => $this->dimmed,
            'text-gray-500 dark:text-gray-300' => ! $this->dimmed,
        ]);
    }
}
