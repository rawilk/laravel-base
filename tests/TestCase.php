<?php

namespace Rawilk\LaravelBase\Tests;

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

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    protected function getPackageProviders($app): array
    {
        return [
            // RayServiceProvider::class,
            LivewireServiceProvider::class,
            FormComponentsServiceProvider::class,
            LaravelBaseServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('view.paths', [__DIR__ . '/Fixtures/Views']);
    }
}
