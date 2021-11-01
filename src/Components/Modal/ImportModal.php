<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Modal;

use Rawilk\LaravelBase\Components\BladeComponent;

class ImportModal extends BladeComponent
{
    public function __construct(
        public null|string $title = null,
        public null|string $button = null,
        public $upload = null,
        public $id = null,
        // Useful if not bundling filepond together with rest of build
        public $assetsPartial = 'layouts.partials.filepond',
        public $showModel = 'showImport',
    ) {
        if (is_null($title)) {
            $this->title = __('laravel-base::messages.modal.import.title');
        }

        if (is_null($button)) {
            $this->button = __('laravel-base::messages.modal.import.button');
        }
    }

    public function id(): null|string
    {
        return $this->id ?? $this->id = md5((string) $this->attributes->wire('model'));
    }
}
