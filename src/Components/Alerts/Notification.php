<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Alerts;

use Rawilk\LaravelBase\Components\BladeComponent;

class Notification extends BladeComponent
{
    public function __construct(
        public string|int $timeout = 5000, // in ms
    ) {
    }

    public function optionsToJson(): string
    {
        return json_encode([
            'timeout' => $this->timeout,
        ]);
    }
}
