<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Modal;

use Rawilk\LaravelBase\Components\BladeComponent;

class DialogModal extends BladeComponent
{
    public function __construct(
        public ?string $id = null,
        public ?string $maxWidth = null,
        public bool $showClose = true,
        public bool $showIcon = true,
        public $title = null,
        public $content = null,
        public $footer = null,
    ) {
    }
}
