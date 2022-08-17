<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Auth;

use Rawilk\LaravelBase\Components\BladeComponent;

class ConfirmsPassword extends BladeComponent
{
    public function __construct(
        public ?string $title = null,
        public ?string $content = null,
        public ?string $button = null,
        public string $confirmButtonVariant = 'blue',
        public string $cancelButtonVariant = 'white',
        public ?string $confirmableId = null,
    ) {
        $this->title = $title ?: __('base::messages.confirms_password.title');
        $this->content = $content ?: __('base::messages.confirms_password.content');
        $this->button = $button ?: __('base::messages.confirms_password.button');
    }
}
