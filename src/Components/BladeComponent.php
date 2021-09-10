<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\View\ComponentSlot;

abstract class BladeComponent extends Component
{
    public function render()
    {
        $alias = Str::kebab(class_basename($this));

        $config = config("laravel-base.components.{$alias}");

        return view($config['view']);
    }

    /**
     * Ensures we always have an instance of ComponentSlot for merging attributes in slots.
     * Useful when the "slot" may not always be provided to the component but we
     * need some default attributes always present.
     *
     * @param mixed $slot
     * @return \Illuminate\View\ComponentSlot
     */
    public function componentSlot(mixed $slot): ComponentSlot
    {
        return $slot instanceof ComponentSlot
            ? $slot
            : new ComponentSlot;
    }
}
