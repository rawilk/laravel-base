<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Lists;

use Rawilk\LaravelBase\Components\BladeComponent;

class DescriptionListItem extends BladeComponent
{
    public function __construct(public $label = '')
    {
    }
}
