<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Rawilk\LaravelBase\LaravelBase;

final class LaravelBaseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // LaravelBase::findAppTimezoneUsing(function () {
        //     return config('app.timezone');
        // });

        $this->setDefaultPasswordRules();
    }

    private function setDefaultPasswordRules(): void
    {
        Password::defaults(function () {
            return Password::min(6)
                ->when($this->app->isProduction(), function (Password $rule) {
                    return $rule->uncompromised();
                });
        });
    }
}
