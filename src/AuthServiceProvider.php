<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Rawilk\LaravelBase\Models\Role;
use Rawilk\LaravelBase\Policies\RolePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Role::class => RolePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        $this->registerSuperAdmin();
    }

    protected function registerSuperAdmin(): void
    {
        // Implicitly grant the "Super Admin" role all permissions.
        // This works in the app by using gate-related functions like auth()->user()->can() and @can().
        Gate::after(function ($user) {
            if (method_exists($user, 'isSuperAdmin')) {
                return $user->isSuperAdmin();
            }

            return null;
        });
    }
}
