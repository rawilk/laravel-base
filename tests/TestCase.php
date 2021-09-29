<?php

namespace Rawilk\LaravelBase\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Livewire\LivewireServiceProvider;
use Mockery;
use Orchestra\Testbench\TestCase as Orchestra;
use Rawilk\FormComponents\FormComponentsServiceProvider;
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

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    protected function getPackageProviders($app): array
    {
        return [
            RayServiceProvider::class,
            LivewireServiceProvider::class,
            FormComponentsServiceProvider::class,
            LaravelBaseServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('view.paths', [__DIR__ . '/Fixtures/Views']);

        // include_once __DIR__ . '/../database/migrations/create_laravel-base_table.php.stub';
        // (new \CreatePackageTable())->up();
    }
}
