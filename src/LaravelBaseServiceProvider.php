<?php

namespace Rawilk\LaravelBase;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Component;
use Rawilk\LaravelBase\Console\InstallCommand;
use Rawilk\LaravelBase\Http\Controllers\LaravelBaseAssets;

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
        $this->bootDirectives();
        $this->bootMacros();
        $this->bootRoutes();
    }

    protected function bootResources(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-base');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'laravel-base');
    }

    protected function bootBladeComponents(): void
    {
        // Allows us to not have to register every single component in the config file.
        Blade::componentNamespace('Rawilk\\LaravelBase\\Components', 'laravel-base');

        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
            $prefix = config('laravel-base.component_prefix', '');

            foreach (config('laravel-base.components', []) as $alias => $component) {
                $componentClass = is_string($component) ? $component : $component['class'];

                $blade->component($componentClass, $alias, $prefix);
            }
        });
    }

    protected function bootDirectives(): void
    {
        Blade::directive('lbJavaScript', function (string $expression) {
            return "<?php echo \\Rawilk\\LaravelBase\\Facades\\LaravelBaseAssets::javaScript({$expression}); ?>";
        });
    }

    protected function bootMacros(): void
    {
        if (class_exists(Component::class)) {
            Component::macro('notify', function (string|null $message, string $type = 'success', array $options = []) {
                /** @var \Livewire\Component $this */
                $this->dispatchBrowserEvent('notify', [
                    'message' => $message,
                    'type' => $type,
                    'id' => (string) Str::uuid(),
                    'autoDismiss' => $options['autoDismiss'] ?? true,
                ]);
            });
        }
    }

    protected function bootRoutes(): void
    {
        Route::get('/laravel-base/laravel-base.js', [LaravelBaseAssets::class, 'source']);
        Route::get('/laravel-base/laravel-base.js.map', [LaravelBaseAssets::class, 'maps']);
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
            __DIR__ . '/../stubs/app/Providers/LaravelBaseServiceProvider.php' => app_path('Providers/LaravelBaseServiceProvider.php'),
            __DIR__ . '/../stubs/resources/css/app.css' => resource_path('css/app.css'),
            __DIR__ . '/../stubs/resources/js/bootstrap.js' => resource_path('js/bootstrap.js'),
            __DIR__ . '/../stubs/build/tailwind-safelist-preset.js' => base_path('tailwind-safelist-preset.js'),
            __DIR__ . '/../stubs/build/tailwind-preset.js' => base_path('tailwind-preset.js'),
            __DIR__ . '/../stubs/build/tailwind.config.js' => base_path('tailwind.config.js'),
            __DIR__ . '/../stubs/build/webpack.mix.js' => base_path('webpack.mix.js'),
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
