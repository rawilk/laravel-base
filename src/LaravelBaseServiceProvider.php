<?php

namespace Rawilk\LaravelBase;

use Illuminate\Support\ServiceProvider;
use Rawilk\LaravelBase\Console\InstallCommand;

class LaravelBaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-base.php', 'laravel-base');
    }

    public function boot(): void
    {
        $this->configurePublishing();
        $this->configureCommands();
    }

    protected function configurePublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../stubs/LaravelBaseServiceProvider.php' => app_path('Providers/LaravelBaseServiceProvider.php'),
        ], 'laravel-base-support');
    }

    protected function configureCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            InstallCommand::class,
        ]);
    }
}
