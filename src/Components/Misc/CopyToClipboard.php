<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Misc;

use Rawilk\LaravelBase\Components\BladeComponent;

class CopyToClipboard extends BladeComponent
{
    public bool $textIsArray = false;

    public function __construct(
        public $text = '',
        public $title = 'laravel-base::messages.copy_to_clipboard',
        public $message = 'laravel-base::messages.copied_to_clipboard',
        public string $icon = 'heroicon-o-clipboard-copy',
        public string $copiedIcon = 'heroicon-s-check',
    ) {
        if ($title !== '') {
            $this->title = __($title);
        }

        if ($message !== '') {
            $this->message = __($message);
        }

        if (is_array($text)) {
            $this->text = str_replace('"', "'", json_encode($text));
            $this->textIsArray = true;
        }
    }
}
