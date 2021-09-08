<?php

namespace Rawilk\LaravelBase\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Rawilk\LaravelBase\LaravelBase
 */
class LaravelBaseFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-base';
    }
}
