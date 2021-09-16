<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Modal;

use Rawilk\LaravelBase\Components\BladeComponent;

class SlideOverForm extends BladeComponent
{
    public function __construct(
        public null | string $id = null,
        public bool $wide = true,
        public bool $showClose = true,
        public $title = null,
        public $subTitle = null,
        public $footer = null,
        public $header = null, // Only for assigning attributes via slot
    ) {
    }
}
