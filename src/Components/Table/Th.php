<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Table;

use Illuminate\Support\Facades\Config;
use Rawilk\LaravelBase\Components\BladeComponent;

class Th extends BladeComponent
{
    public ?string $defaultClass;

    public function __construct(
        public bool $hidden = false,
        public ?string $scope = 'col',
        public ?string $role = 'columnheader',
        public bool|string|int $colIndex = false,
        public bool $sortable = false,
        public ?string $direction = null,
        public string $align = 'left',
        public bool $nowrap = false,
    ) {
        $this->defaultClass = Config::get('laravel-base.components.th.default_class');
    }
}
