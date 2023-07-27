<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Navigation;

use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Rawilk\LaravelBase\Components\BladeComponent;
use Rawilk\LaravelBase\Services\Popper;

class Dropdown extends BladeComponent
{
    private ?string $triggerId = null;

    private ?string $menuId = null;

    public function __construct(
        public bool $right = false,
        public bool $splitButton = false,
        public string $buttonVariant = 'outlined', // Only applies when $triggerText is supplied
        public string $buttonColor = 'slate', // Only applies when $triggerText is supplied
        public ?string $triggerText = null,
        public bool $disabled = false,
        public ?string $size = null, // Only applies when $triggerText is supplied
        public ?string $placement = null,
        public bool $dropUp = false,
        public string|int $offset = 8,
        public bool $fixed = false,
        public null|string|bool $id = null,
        public string $widthClass = 'w-56', // For the menu itself
        public bool $padMenu = true,
        public $trigger = null, // To use a custom trigger
        public $menu = null, // For assigning attributes to the inner attribute via slot
    ) {
        if ($this->id !== false) {
            $this->id = $this->id ?? uniqid('dropdown', false);
        }
    }

    public function config(): array
    {
        return [
            'disabled' => $this->disabled,
            'offset' => $this->offset,
            'fixed' => $this->fixed,
            'placement' => $this->getPlacement(),
        ];
    }

    public function menuClass(): string
    {
        return Arr::toCssClasses([
            'py-1' => $this->padMenu,
        ]);
    }

    public function menuId(): string
    {
        if (! is_null($this->menuId)) {
            return $this->menuId;
        }

        return $this->menuId = "menu-{$this->id}";
    }

    public function triggerId(): string
    {
        if (! is_null($this->triggerId)) {
            return $this->triggerId;
        }

        return $this->triggerId = "trigger-{$this->id}";
    }

    public function triggerAttributes(): array
    {
        return [
            'x-ref' => 'trigger',
            'x-on:click' => 'toggleMenu',
            'aria-haspopup' => 'true',
            'x-bind:aria-expanded' => 'JSON.stringify(open)',
            'id' => $this->id === false ? null : $this->triggerId(),
            'aria-controls' => $this->id === false ? null : $this->menuId(),
        ];
    }

    public function triggerAttributesToString(): HtmlString
    {
        $attributes = collect($this->triggerAttributes())
            ->filter()
            ->map(fn ($value, $key) => "{$key}=\"{$value}\"")
            ->implode(PHP_EOL);

        return new HtmlString($attributes);
    }

    public function configToJson(): string
    {
        return '...' . json_encode((object) $this->config()) . ',';
    }

    private function getPlacement(): string
    {
        if (! is_null($this->placement)) {
            return $this->placement;
        }

        return match (true) {
            $this->dropUp && $this->right => Popper::PLACEMENT_TOP_RIGHT,
            $this->dropUp && ! $this->right => Popper::PLACEMENT_TOP_LEFT,
            ! $this->dropUp && $this->right => Popper::PLACEMENT_BOTTOM_RIGHT,
            default => Popper::PLACEMENT_BOTTOM_LEFT,
        };
    }
}
