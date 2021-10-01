<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\VendorPublishCommand;
use Illuminate\Session\Console\SessionTableCommand;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

final class InstallCommand extends Command
{
    protected $signature = 'laravel-base:install
                            {--composer=global : Absolute path to the Composer binary which should be used to install packages}';

    protected $description = 'Install the laravel-base components and resources.';

    public function handle(): void
    {
        // Publish...
        $this->callSilent(VendorPublishCommand::class, ['--tag' => 'laravel-base-config', '--force' => true]);
        $this->callSilent(VendorPublishCommand::class, ['--tag' => 'laravel-base-support', '--force' => true]);
        $this->callSilent(VendorPublishCommand::class, ['--tag' => 'laravel-base-migrations', '--force' => true]);

        // LaravelBase Provider...
        $this->installServiceProviderAfter('RouteServiceProvider', 'LaravelBaseServiceProvider');

        // Configure Session...
        $this->configureSession();

        // AuthenticateSession Middleware...
        $this->replaceInFile(
            '// \Illuminate\Session\Middleware\AuthenticateSession::class',
            '\Rawilk\LaravelBase\Http\Middleware\AuthenticateSession::class',
            app_path('Http/Kernel.php')
        );

        $this->installLivewireStack();

        // Tests...
        copy(__DIR__ . '/../../stubs/tests/TestCase.php', base_path('tests/TestCase.php'));

        $this->line('');
        $this->info('LaravelBase scaffolding installed successfully.');
    }

    private function installLivewireStack(): void
    {
        // Install Packages...
        $this->requireComposerPackages(
            'livewire/livewire',
            'spatie/laravel-menu',
            'rawilk/laravel-form-components',
            'rawilk/laravel-casters',
            'khatabwedaa/blade-css-icons',
            'blade-ui-kit/blade-heroicons',
        );

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@popperjs/core' => '^2.10.1',
                '@ryangjchandler/alpine-clipboard' => '^2.0.0',
                '@tailwindcss/aspect-ratio' => '^0.2.1',
                '@tailwindcss/forms' => '^0.3.3',
                '@tailwindcss/typography' => '^0.4.1',
                'alpinejs' => '^3.4.2',
                'filepond' => '^4.29.1',
                'flatpickr' => '^4.6.9',
                'laravel-mix' => '^6.0.31',
                'postcss-import' => '^14.0.2',
                'tailwindcss' => '^2.2.15',
            ] + $packages;
        });

        // Tailwind Configuration...
        copy(__DIR__ . '/../../stubs/build/tailwind-safelist-preset.js', base_path('tailwind-safelist-preset.js'));
        copy(__DIR__ . '/../../stubs/build/tailwind-preset.js', base_path('tailwind-preset.js'));
        copy(__DIR__ . '/../../stubs/build/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__ . '/../../stubs/build/webpack.mix.js', base_path('webpack.mix.js'));

        // Directories...
        (new Filesystem)->ensureDirectoryExists(app_path('Actions/LaravelBase'));
        (new Filesystem)->ensureDirectoryExists(app_path('Actions/Auth'));
        (new Filesystem)->ensureDirectoryExists(public_path('css'));
        (new Filesystem)->ensureDirectoryExists(resource_path('css'));
        (new Filesystem)->ensureDirectoryExists(resource_path('views/components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('views/layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('views/livewire'));
        (new Filesystem)->ensureDirectoryExists(resource_path('views/pages'));
        (new Filesystem)->ensureDirectoryExists(app_path('Http/ViewComposers'));
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Livewire'));
        (new Filesystem)->ensureDirectoryExists(app_path('Services'));
        (new Filesystem)->ensureDirectoryExists(app_path('Support'));
        (new Filesystem)->ensureDirectoryExists(app_path('Support/Auth'));
        (new Filesystem)->ensureDirectoryExists(app_path('Notifications/Users'));
        (new Filesystem)->ensureDirectoryExists(app_path('Listeners/Users'));
        (new Filesystem)->ensureDirectoryExists(app_path('Helpers'));
        (new Filesystem)->ensureDirectoryExists(app_path('Models/User'));

        (new Filesystem)->deleteDirectory(resource_path('sass'));

        // Service Providers...
        copy(__DIR__ . '/../../stubs/app/Providers/AuthServiceProvider.php', app_path('Providers/AuthServiceProvider.php'));
        copy(__DIR__ . '/../../stubs/app/Providers/ViewComposerServiceProvider.php', app_path('Providers/ViewComposerServiceProvider.php'));
        copy(__DIR__ . '/../../stubs/app/Providers/BladeComponentsServiceProvider.php', app_path('Providers/BladeComponentsServiceProvider.php'));
        copy(__DIR__ . '/../../stubs/app/Providers/NavigationServiceProvider.php', app_path('Providers/NavigationServiceProvider.php'));
        $this->installServiceProviderAfter('LaravelBaseServiceProvider', 'ViewComposerServiceProvider');
        $this->installServiceProviderAfter('ViewComposerServiceProvider', 'BladeComponentsServiceProvider');
        $this->installServiceProviderAfter('BladeComponentsServiceProvider', 'NavigationServiceProvider');

        // Support...
        copy(__DIR__ . '/../../stubs/app/Support/Queues.php', app_path('Support/Queues.php'));
        copy(__DIR__ . '/../../stubs/app/Support/Auth/CustomUserProvider.php', app_path('Support/Auth/CustomUserProvider.php'));

        // Services...
        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/app/Services/Menus', app_path('Services/Menus'));

        // Notifications...
        copy(__DIR__ . '/../../stubs/app/Notifications/Users/WelcomeNotification.php', app_path('Notifications/Users/WelcomeNotification.php'));

        // Listeners...
        copy(__DIR__ . '/../../stubs/app/Listeners/Users/RegisteredListener.php', app_path('Listeners/Users/RegisteredListener.php'));

        // Helpers...
        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/app/Helpers', app_path('Helpers'));
        $this->addHelpersToComposer();

        // Models...
        if (file_exists(app_path('Models/User.php'))) {
            (new Filesystem)->delete(app_path('Models/User.php'));
        }

        copy(__DIR__ . '/../../stubs/app/Models/User/User.php', app_path('Models/User/User.php'));

        // Factories...
        copy(__DIR__ . '/../../database/factories/UserFactory.php', base_path('database/factories/UserFactory.php'));

        // Actions...
        copy(__DIR__ . '/../../stubs/app/Actions/LaravelBase/DeleteUserAction.php', app_path('Actions/LaravelBase/DeleteUserAction.php'));
        copy(__DIR__ . '/../../stubs/app/Actions/LaravelBase/UpdatePasswordAction.php', app_path('Actions/LaravelBase/UpdatePasswordAction.php'));
        copy(__DIR__ . '/../../stubs/app/Actions/LaravelBase/UpdateUserProfileInformationAction.php', app_path('Actions/LaravelBase/UpdateUserProfileInformationAction.php'));

        // Views...
        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/resources/views/components', resource_path('views/components'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/resources/views/layouts', resource_path('views/layouts'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/resources/views/livewire', resource_path('views/livewire'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/resources/views/pages', resource_path('views/pages'));

        // View Composers...
        copy(__DIR__ . '/../../stubs/app/Http/ViewComposers/SessionAlertViewComposer.php', app_path('Http/ViewComposers/SessionAlertViewComposer.php'));

        // Assets...
        copy(__DIR__ . '/../../stubs/resources/css/app.css', resource_path('css/app.css'));
        copy(__DIR__ . '/../../stubs/resources/js/bootstrap.js', resource_path('js/bootstrap.js'));

        // Config...
        copy(__DIR__ . '/../../stubs/config/site.php', base_path('config/site.php'));
        $this->replaceInFile('App\Models\User::class', 'App\Models\User\User::class', base_path('config/auth.php'));
        $this->replaceInFile("'eloquent'", "'customEloquent'", base_path('config/auth.php'));

        // Tests...
        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/tests/Feature', base_path('tests/Feature'));

        $this->line('');
        $this->info('Livewire scaffolding installed successfully.');
        $this->comment('Please execute "npm install && npx mix" to build your assets.');
    }

    private function configureSession(): void
    {
        if (! class_exists('CreateSessionsTable')) {
            try {
                $this->call(SessionTableCommand::class);
            } catch (Exception) {}
        }

        $this->replaceInFile("'SESSION_DRIVER', 'file'", "'SESSION_DRIVER', 'database'", config_path('session.php'));
        $this->replaceInFile('SESSION_DRIVER=file', 'SESSION_DRIVER=database', base_path('.env'));
        $this->replaceInFile('SESSION_DRIVER=file', 'SESSION_DRIVER=database', base_path('.env.example'));
    }

    private function installServiceProviderAfter($after, $name): void
    {
        if (! Str::contains($appConfig = file_get_contents(config_path('app.php')), 'App\\Providers\\' . $name . '::class')) {
            file_put_contents(config_path('app.php'), str_replace(
                'App\\Providers\\' . $after . '::class,',
                'App\\Providers\\' . $after . '::class,' . PHP_EOL . '        App\\Providers\\' . $name . '::class,',
                $appConfig
            ));
        }
    }

    private function replaceInFile(string $search, string $replace, string $path): void
    {
        file_put_contents(
            $path,
            str_replace($search, $replace, file_get_contents($path))
        );
    }

    private function requireComposerPackages($packages): void
    {
        $composer = $this->option('composer');

        if ($composer !== 'global') {
            $command = ['php', $composer, 'require'];
        }

        $command = array_merge(
            $command ?? ['composer', 'require'],
            is_array($packages) ? $packages : func_get_args()
        );

        /** @psalm-suppress UnusedClosureParam */
        (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }

    /**
     * Update the "package.json" file.
     *
     * @param callable $callback
     * @param bool $dev
     */
    private function updateNodePackages(callable $callback, bool $dev = true): void
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }

    private function addHelpersToComposer(): void
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);

        $composer['autoload']['files'] = [
            'app/Helpers/helpers.php',
        ];

        file_put_contents(
            base_path('composer.json'),
            json_encode($composer, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }
}
