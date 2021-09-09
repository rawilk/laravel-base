<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Rawilk\LaravelBase\LaravelBase;

final class LaravelBaseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // LaravelBase::findAppTimezoneUsing(function () {
        //     return config('app.timezone');
        // });
    }
}
