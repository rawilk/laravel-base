<?php

namespace Rawilk\LaravelBase;

use Rawilk\LaravelBase\Commands\LaravelBaseCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelBaseServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-base')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-base_table')
            ->hasCommand(LaravelBaseCommand::class);
    }
}
