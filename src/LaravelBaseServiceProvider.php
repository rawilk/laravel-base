<?php

namespace Rawilk\LaravelBase;

use Illuminate\Support\ServiceProvider;

class LaravelBaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-base.php', 'laravel-base');
    }

    public function boot(): void
    {
    }
}
