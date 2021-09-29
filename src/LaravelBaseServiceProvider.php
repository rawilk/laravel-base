<?php

namespace Rawilk\LaravelBase;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Component;
use Livewire\Livewire;
use Rawilk\LaravelBase\Console\InstallCommand;
use Rawilk\LaravelBase\Contracts;
use Rawilk\LaravelBase\Http\Controllers\LaravelBaseAssets;
use Rawilk\LaravelBase\Http\Livewire\Auth\ConfirmPassword;
use Rawilk\LaravelBase\Http\Livewire\Auth\Passwords\Email;
use Rawilk\LaravelBase\Http\Livewire\Auth\Passwords\Reset;
use Rawilk\LaravelBase\Http\Livewire\Auth\TwoFactorLogin;
use Rawilk\LaravelBase\Http\Livewire\Auth\Verify;
use Rawilk\LaravelBase\Http\Livewire\Profile\DeleteUserForm;
use Rawilk\LaravelBase\Http\Livewire\Profile\LogoutOtherBrowserSessionsForm;
use Rawilk\LaravelBase\Http\Livewire\Profile\ProfileNavigationMenu;
use Rawilk\LaravelBase\Http\Livewire\Profile\TwoFactorAuthenticationForm;
use Rawilk\LaravelBase\Http\Livewire\Profile\UpdatePasswordForm;
use Rawilk\LaravelBase\Http\Livewire\Profile\UpdateProfileInformationForm;
use Rawilk\LaravelBase\Http\Responses;

class LaravelBaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-base.php', 'laravel-base');

        $this->registerResponseBindings();

        $this->app->singleton(
            Contracts\Auth\TwoFactorAuthenticationProvider::class,
            TwoFactorAuthenticationProvider::class
        );

        $this->app->bind(
            StatefulGuard::class,
            fn () => Auth::guard(config('laravel-base.guard'))
        );

        $this->app->register(EventServiceProvider::class);
    }

    public function boot(): void
    {
        $this->configurePublishing();
        $this->configureRoutes();
        $this->configureCommands();

        $this->bootResources();
        $this->bootBladeComponents();
        $this->bootLivewire();
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

    protected function bootLivewire(): void
    {
        if (! class_exists(Livewire::class)) {
            return;
        }

        Livewire::component('login', Config::get('laravel-base.livewire.login'));

        if (Features::enabled(Features::registration())) {
            Livewire::component('register', Config::get('laravel-base.livewire.register'));
        }

        if (Features::enabled(Features::emailVerification())) {
            Livewire::component('verify-email', Verify::class);
        }

        if (Features::enabled(Features::resetPasswords())) {
            Livewire::component('password.email', Email::class);
            Livewire::component('password.reset', Reset::class);
        }

        if (Features::canManageTwoFactorAuthentication()) {
            Livewire::component('two-factor-challenge', TwoFactorLogin::class);
        }

        Livewire::component('profile-navigation-menu', ProfileNavigationMenu::class);
        Livewire::component('profile.update-profile-information-form', UpdateProfileInformationForm::class);
        Livewire::component('profile.update-password-form', UpdatePasswordForm::class);
        Livewire::component('profile.two-factor-authentication-form', TwoFactorAuthenticationForm::class);
        Livewire::component('profile.delete-user-form', DeleteUserForm::class);
        Livewire::component('profile.logout-other-browser-sessions-form', LogoutOtherBrowserSessionsForm::class);
        Livewire::component('password.confirm', ConfirmPassword::class);
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

    protected function registerResponseBindings(): void
    {
        $this->app->singleton(Contracts\Auth\LockoutResponse::class, Responses\Auth\LockoutResponse::class);
        $this->app->singleton(Contracts\Auth\LoginResponse::class, Responses\Auth\LoginResponse::class);
        $this->app->singleton(Contracts\Auth\LogoutResponse::class, Responses\Auth\LogoutResponse::class);
        $this->app->singleton(Contracts\Auth\RegisterResponse::class, Responses\Auth\RegisterResponse::class);
        $this->app->singleton(Contracts\Auth\FailedPasswordResetLinkRequestResponse::class, Responses\Auth\FailedPasswordResetLinkRequestResponse::class);
        $this->app->singleton(Contracts\Auth\FailedTwoFactorLoginResponse::class, Responses\Auth\FailedTwoFactorLoginResponse::class);
        $this->app->singleton(Contracts\Auth\SuccessfulPasswordResetLinkRequestResponse::class, Responses\Auth\SuccessfulPasswordResetLinkRequestResponse::class);
        $this->app->singleton(Contracts\Auth\PasswordResetResponse::class, Responses\Auth\PasswordResetResponse::class);
        $this->app->singleton(Contracts\Auth\FailedPasswordResetResponse::class, Responses\Auth\FailedPasswordResetResponse::class);
        $this->app->singleton(Contracts\Auth\TwoFactorLoginResponse::class, Responses\Auth\TwoFactorLoginResponse::class);
        $this->app->singleton(Contracts\Auth\PasswordConfirmedResponse::class, Responses\Auth\PasswordConfirmedResponse::class);
        $this->app->singleton(Contracts\Auth\FailedPasswordConfirmationResponse::class, Responses\Auth\FailedPasswordConfirmationResponse::class);
    }

    protected function configureRoutes(): void
    {
        if (! LaravelBase::$registersRoutes) {
            return;
        }

        Route::group([
            'domain' => config('laravel-base.domain'),
            'prefix' => config('laravel-base.prefix'),
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
        });
    }
}
