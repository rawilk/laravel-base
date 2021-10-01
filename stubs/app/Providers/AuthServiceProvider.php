<?php

declare(strict_types=1);

namespace App\Providers;

use App\Support\Auth\CustomUserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

final class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }

    public function register(): void
    {
        parent::register();

        Auth::provider('customEloquent', function ($app, array $config) {
            return new CustomUserProvider($app->make('hash'), $config['model']);
        });
    }
}
