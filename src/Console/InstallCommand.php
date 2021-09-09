<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Console\VendorPublishCommand;
use Illuminate\Support\Str;

final class InstallCommand extends Command
{
    protected $signature = 'laravel-base:install';

    protected $description = 'Install the laravel-base components and resources.';

    public function handle(): void
    {
        // Publish...
        $this->callSilent(VendorPublishCommand::class, ['--tag' => 'laravel-base-config', '--force' => true]);
        $this->callSilent(VendorPublishCommand::class, ['--tag' => 'laravel-base-support', '--force' => true]);

        // LaravelBase Provider...
        $this->installServiceProviderAfter('RouteServiceProvider', 'LaravelBaseServiceProvider');

        $this->line('');
        $this->info('LaravelBase scaffolding installed successfully.');
    }

    protected function installServiceProviderAfter($after, $name): void
    {
        if (! Str::contains($appConfig = file_get_contents(config_path('app.php')), 'App\\Providers\\' . $name . '::class')) {
            file_put_contents(config_path('app.php'), str_replace(
                'App\\Providers\\' . $after . '::class,',
                'App\\Providers\\' . $after . '::class,' . PHP_EOL . '        App\\Providers\\' . $name . '::class,',
                $appConfig
            ));
        }
    }
}
