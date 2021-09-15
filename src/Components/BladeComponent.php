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
        return view("laravel-base::components.{$this::getName()}");
    }

    /*
     * This method is pretty much a direct copy of how livewire/livewire
     * determines which view to render in Component.php.
     */
    public static function getName(): string
    {
        $namespace = collect(explode('.', Str::replace(['/', '\\'], '.', 'Rawilk\\LaravelBase\\Components')))
            ->map([Str::class, 'kebab'])
            ->implode('.');

        $fullName = collect(explode('.', str_replace(['/', '\\'], '.', static::class)))
            ->map([Str::class, 'kebab'])
            ->implode('.');

        if (Str::startsWith($fullName, $namespace)) {
            return (string) Str::of($fullName)->substr(strlen($namespace) + 1);
        }

        return $fullName;
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
