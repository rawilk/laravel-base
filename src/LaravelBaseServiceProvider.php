<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase;

use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Component;
use Livewire\Livewire;
use Rawilk\LaravelBase\Console\InstallCommand;
use Rawilk\LaravelBase\Contracts\Models\AuthenticatorApp as AuthenticatorAppContract;
use Rawilk\LaravelBase\Contracts\Models\ImpersonatesUsers;
use Rawilk\LaravelBase\Contracts\Models\Import as ImportContract;
use Rawilk\LaravelBase\Http\Controllers\LaravelBaseAssets;
use Rawilk\LaravelBase\Http\Livewire\CsvImporter;
use Rawilk\LaravelBase\Http\Livewire\CsvImports;
use Rawilk\LaravelBase\Http\Responses;
use Rawilk\LaravelBase\Models\AuthenticatorApp;
use Rawilk\LaravelBase\Services\Auth\CustomSessionGuard;
use Rawilk\LaravelBase\Services\Auth\SessionImpersonator;
use Rawilk\LaravelBase\Services\Files\SizeUnitFactor;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelBaseServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-base')
            ->hasConfigFile('laravel-base')
            ->hasViews('laravel-base')
            ->hasCommands([
                InstallCommand::class,
            ])
            ->hasMigrations([
                'create_users_table',
                'create_permission_tables',
            ])
            ->hasTranslations();
    }

    public function packageRegistered(): void
    {
        $this->registerResponseBindings();
        $this->registerAuthDriver();

        $this->app->singleton(
            Contracts\Auth\TwoFactorAuthenticationProvider::class,
            TwoFactorAuthenticationProvider::class
        );

        $this->app->bind(
            StatefulGuard::class,
            fn () => Auth::guard(config('laravel-base.guard'))
        );

        $this->app->singleton(ImpersonatesUsers::class, SessionImpersonator::class);

        $this->app->register(EventServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);

        // For file size formatting
        $this->app->singleton(SizeUnitFactor::class, fn () => new SizeUnitFactor);
    }

    public function packageBooted(): void
    {
        $this->configureRoutes();
        $this->bootBladeComponents();
        $this->bootLivewire();
        $this->bootDirectives();
        $this->bootMacros();
        $this->bootRoutes();
        $this->bootEvents();

        AboutCommand::add('Laravel Base', fn () => [
            'Version' => LaravelBase::VERSION,
            'Asset URL' => config('laravel-base.asset_url') ?? 'default',
            'Admin Route Prefix' => config('laravel-base.admin_route_prefix'),
        ]);
    }

    /*
     * Registering...
     */

    protected function registerAuthDriver(): void
    {
        /** @var \Illuminate\Auth\AuthManager $auth */
        $auth = $this->app['auth'];

        $auth->extend('session', function (Application $app, $name, array $config) use ($auth) {
            $provider = $auth->createUserProvider($config['provider']);

            $guard = new CustomSessionGuard($name, $provider, $app['session.store']);

            if (method_exists($guard, 'setCookieJar')) {
                $guard->setCookieJar($app['cookie']);
            }

            if (method_exists($guard, 'setDispatcher')) {
                $guard->setDispatcher($app['events']);
            }

            if (method_exists($guard, 'setRequest')) {
                $guard->setRequest($app->refresh('request', $guard, 'setRequest'));
            }

            return $guard;
        });
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

        // Models
        $this->app->bind(AuthenticatorAppContract::class, config('laravel-base.authenticator_apps.model', AuthenticatorApp::class));
        $this->app->bind(ImportContract::class, config('laravel-base.models.import', \App\Models\Imports\Import::class));
    }

    /*
     * Booting...
     */

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

    protected function bootEvents(): void
    {
        tap($this->app['events'], function ($event) {
            $event->listen(Attempting::class, function () {
                app(ImpersonatesUsers::class)->flushImpersonationData(request());
            });

            $event->listen(Logout::class, function () {
                app(ImpersonatesUsers::class)->flushImpersonationData(request());
            });
        });
    }

    protected function bootLivewire(): void
    {
        if (! class_exists(Component::class)) {
            return;
        }

        Livewire::component('login', Config::get('laravel-base.livewire.login', Http\Livewire\Auth\Login::class));

        if (Features::enabled(Features::registration())) {
            Livewire::component('register', Config::get('laravel-base.livewire.register', Http\Livewire\Auth\Register::class));
        }

        if (Features::enabled(Features::emailVerification())) {
            Livewire::component('verify-email', Http\Livewire\Auth\Verify::class);
        }

        if (Features::enabled(Features::resetPasswords())) {
            Livewire::component('password.email', Http\Livewire\Auth\Passwords\Email::class);
            Livewire::component('password.reset', Http\Livewire\Auth\Passwords\Reset::class);
        }

        if (Features::canManageTwoFactorAuthentication()) {
            Livewire::component('profile.two-factor-authentication-form', Http\Livewire\Profile\TwoFactorAuthenticationForm::class);
        }

        if (Features::canManageWebauthnAuthentication()) {
            Livewire::component('profile.webauthn-security-keys-form', Http\Livewire\Profile\WebauthnSecurityKeysForm::class);
            Livewire::component('profile.webauthn-internal-keys-form', Http\Livewire\Profile\WebauthnInternalKeysForm::class);
        }

        if (Features::canManageWebauthnAuthentication() || Features::canManageTwoFactorAuthentication()) {
            Livewire::component('two-factor-challenge', Http\Livewire\Auth\TwoFactorLogin::class);
            Livewire::component('profile.2fa-recovery-codes', Http\Livewire\Profile\TwoFactorRecoveryCodes::class);
        }

        Livewire::component('profile-navigation-menu', Http\Livewire\Profile\ProfileNavigationMenu::class);
        Livewire::component('profile.update-profile-information-form', Http\Livewire\Profile\UpdateProfileInformationForm::class);
        Livewire::component('profile.update-password-form', Http\Livewire\Profile\UpdatePasswordForm::class);
        Livewire::component('profile.delete-user-form', Http\Livewire\Profile\DeleteUserForm::class);
        Livewire::component('profile.logout-other-browser-sessions-form', Http\Livewire\Profile\LogoutOtherBrowserSessionsForm::class);
        Livewire::component('password.confirm', Http\Livewire\Auth\ConfirmPassword::class);

        if (Features::managesRoles()) {
            Livewire::component('admin.roles.index', Config::get('laravel-base.livewire.roles.index', Http\Livewire\Roles\Index::class));
            Livewire::component('admin.roles.create', Config::get('laravel-base.livewire.roles.create', Http\Livewire\Roles\Create::class));
            Livewire::component('admin.roles.edit', Config::get('laravel-base.livewire.roles.edit', Http\Livewire\Roles\Edit::class));
        }

        Livewire::component('csv-importer', CsvImporter::class);
        Livewire::component('csv-imports', CsvImports::class);
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

        Blueprint::macro('dateTimestamps', function () {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $this->dateTime('created_at')->nullable();
            $this->dateTime('updated_at')->nullable();
        });

        Builder::macro('modelSearch', function (array|string $field, string|null $string, $boolean = 'and') {
            /** @var \Illuminate\Database\Eloquent\Builder $this */
            if (is_array($field) && ! empty($string)) {
                return $this->where(function ($query) use ($field, $string) {
                    foreach ($field as $searchField) {
                        $query->orWhere($searchField, 'LIKE', "%{$string}%");
                    }
                }, boolean: $boolean);
            }

            return $string ? $this->where($field, 'LIKE', "%{$string}%", boolean: $boolean) : $this;
        });

        if (! Builder::hasGlobalMacro('toSqlWithBindings')) {
            Builder::macro('toSqlWithBindings', function () {
                /** @var \Illuminate\Database\Eloquent\Builder $this */
                $sql = str_replace(['?'], ["'%s'"], $this->toSql());

                return vsprintf($sql, $this->getBindings());
            });
        }
    }

    protected function bootRoutes(): void
    {
        Route::get('/laravel-base/assets/{asset}', [LaravelBaseAssets::class, 'source']);
    }

    protected function configureRoutes(): void
    {
        /*
         * Most of our routes are bound to Livewire components, so if Livewire has not been
         * installed yet, it will break the application.
         *
         * TODO: remove livewire binding from routes.
         */
        if (! class_exists(Component::class)) {
            return;
        }

        if (! LaravelBase::$registersRoutes) {
            return;
        }

        Route::group([
            'domain' => config('laravel-base.domain'),
            'prefix' => config('laravel-base.prefix'),
        ], fn () => $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php'));

        Route::group(array_filter([
            'domain' => config('laravel-base.domain'),
            'prefix' => config('laravel-base.admin_route_prefix'),
            'as' => config('laravel-base.admin_route_name_prefix'),
        ]), fn () => $this->loadRoutesFrom(__DIR__ . '/../routes/admin-routes.php'));
    }
}
