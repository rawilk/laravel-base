<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Elements;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Rawilk\LaravelBase\Components\BladeComponent;

class Tooltip extends BladeComponent
{
    // Placements
    public const TOP = 'top';
    public const BOTTOM = 'bottom';
    public const LEFT = 'left';
    public const RIGHT = 'right';

    // Triggers
    public const CLICK = 'click';
    public const HOVER = 'hover';
    public const FOCUS = 'focus';

    public function __construct(
        public string $placement = '',
        public null | string $title = '',
        private null | string | array | Collection $triggers = null,
    ) {
        if (! $placement) {
            $this->placement = static::TOP;
        }

        if (is_null($triggers)) {
            $this->triggers = [static::HOVER, static::FOCUS];
        }

        if (is_string($this->triggers)) {
            $this->triggers = Arr::wrap($this->triggers);
        }

        if (! $this->triggers instanceof Collection) {
            $this->triggers = collect($this->triggers);
        }
    }

    public function triggerEventListeners(): HtmlString
    {
        $hasHover = $this->hasHoverTrigger();
        $hasClick = $this->hasClickTrigger();
        $hasFocus = $this->hasFocusTrigger();

        $triggers = collect([
            $hasHover ? 'x-on:mouseenter="show"' : null,
            $hasHover ? 'x-on:mouseleave="hide"' : null,
            $hasClick ? 'x-on:click="toggle"' : null,
            $hasFocus ? 'x-on:focusin="show"' : null,
            $hasFocus ? 'x-on:focusout="hide"' : null,
        ])->filter();

        return new HtmlString($triggers->implode(PHP_EOL));
    }

    private function hasHoverTrigger(): bool
    {
        return $this->triggers->contains(static::HOVER);
    }

    private function hasClickTrigger(): bool
    {
        return $this->triggers->contains(static::CLICK);
    }

    private function hasFocusTrigger(): bool
    {
        return $this->triggers->contains(static::FOCUS);
    }
}
