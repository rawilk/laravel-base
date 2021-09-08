<?php

namespace Rawilk\LaravelBase;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Rawilk\LaravelBase\Commands\LaravelBaseCommand;

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
