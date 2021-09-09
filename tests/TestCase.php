<?php

namespace Rawilk\LaravelBase\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Rawilk\LaravelBase\LaravelBaseServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Factory::guessFactoryNamesUsing(
        //     fn (string $modelName) => 'Rawilk\\LaravelBase\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        // );
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelBaseServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        // include_once __DIR__ . '/../database/migrations/create_laravel-base_table.php.stub';
        // (new \CreatePackageTable())->up();
    }
}
