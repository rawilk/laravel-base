<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Facades;

use Illuminate\Support\Facades\Facade;
use Rawilk\LaravelBase\Services\Assets;

/**
 * @method static string javaScript(array $options = [])
 *
 * @see \Rawilk\LaravelBase\Services\Assets
 */
class LaravelBaseAssets extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Assets::class;
    }
}
