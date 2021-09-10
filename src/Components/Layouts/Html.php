<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Layouts;

use Rawilk\LaravelBase\Components\BladeComponent;

class Html extends BladeComponent
{
    public function __construct(
        public string $title = '',
        public $html = null, // Only use as a slot to make use of slot attributes
    ) {
    }

    public function title(): string
    {
        return $this->title ?: (string) appName();
    }
}
