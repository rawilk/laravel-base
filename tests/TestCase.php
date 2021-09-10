<?php

namespace Rawilk\LaravelBase\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Rawilk\LaravelBase\LaravelBaseServiceProvider;
use Spatie\LaravelRay\RayServiceProvider;

class TestCase extends Orchestra
{
    use InteractsWithViews;

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
            RayServiceProvider::class,
            LivewireServiceProvider::class,
            LaravelBaseServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        // include_once __DIR__ . '/../database/migrations/create_laravel-base_table.php.stub';
        // (new \CreatePackageTable())->up();
    }
}
