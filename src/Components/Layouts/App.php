<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Layouts;

use Rawilk\LaravelBase\Components\BladeComponent;

class App extends BladeComponent
{
    public function __construct(
        public string $title = '',
        public string $titleSeparator = '|',
        public bool $livewire = true,
        public bool $laravelFormComponents = true,
        public bool $bladeAssets = true,
        public bool $assets = true, // Option only here to make testing easier...
    ) {
    }

    public function title(): string
    {
        return $this->title
            ? "{$this->title} {$this->titleSeparator} " . appName()
            : (string) appName();
    }
}
