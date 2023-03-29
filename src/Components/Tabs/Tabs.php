<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Tabs;

use Illuminate\Support\Str;
use Rawilk\LaravelBase\Components\BladeComponent;

class Tabs extends BladeComponent
{
    // Nav style types
    public const BAR = 'bar';

    public const UNDERLINE = 'underline';

    public const PILLS = 'pills';

    public function __construct(
        public ?string $id = null,
        public ?string $navStyle = null,
        public bool $fullWidthTabs = false, // Only applies to UNDERLINE style
    ) {
        $this->id = $this->id ?? 'tabs-' . Str::random(6);
        $this->navStyle = $navStyle ?? static::BAR;
    }

    public function navView(): string
    {
        return "laravel-base::components.tabs.nav-styles.{$this->navStyle}";
    }
}
