<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Modal;

use Rawilk\LaravelBase\Components\BladeComponent;

class SlideOver extends BladeComponent
{
    public function __construct(
        public null|string $id = null,
        public bool $wide = true,
        public $header = null,
        public $footer = null,
    ) {
    }

    public function id(): null|string
    {
        return $this->id ?? md5((string) $this->attributes->wire('model'));
    }
}
