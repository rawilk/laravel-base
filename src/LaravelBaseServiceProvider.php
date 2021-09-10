<?php

namespace Rawilk\LaravelBase;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
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

        $this->bootResources();
        $this->bootBladeComponents();
    }

    protected function bootResources(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-base');
    }

    protected function bootBladeComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
            $prefix = config('laravel-base.component_prefix', '');

            foreach (config('laravel-base.components', []) as $alias => $component) {
                $blade->component($component['class'], $alias, $prefix);
            }
        });
    }

    protected function configurePublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../config/laravel-base.php' => config_path('laravel-base.php'),
        ], 'laravel-base-config');

        $this->publishes([
            __DIR__ . '/../stubs/LaravelBaseServiceProvider.php' => app_path('Providers/LaravelBaseServiceProvider.php'),
        ], 'laravel-base-support');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/laravel-base'),
        ], 'laravel-base-views');
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
