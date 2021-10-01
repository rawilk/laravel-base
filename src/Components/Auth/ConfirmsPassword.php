<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Auth;

use Rawilk\LaravelBase\Components\BladeComponent;

class ConfirmsPassword extends BladeComponent
{
    public function __construct(
        public null | string $title = 'laravel-base::messages.confirms_password.title',
        public null | string $content = 'laravel-base::messages.confirms_password.content',
        public null | string $button = 'laravel-base::messages.confirms_password.button',
        public string $confirmButtonVariant = 'blue',
        public string $cancelButtonVariant = 'white',
    ) {
        $this->title = __($title);
        $this->content = __($content);
        $this->button = __($button);
    }
}
