<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Services;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Config;

class Routing
{
    /**
     * Determine what the "home" url of the application should be.
     *
     * @return string|null
     */
    public static function home(): null|string
    {
        if (function_exists('homeRoute')) {
            return homeRoute();
        }

        $default = class_exists(RouteServiceProvider::class)
            ? RouteServiceProvider::HOME
            : '/';

        $home = Config::get('laravel-base.home');

        return is_null($home) ? $default : $home;
    }
}
