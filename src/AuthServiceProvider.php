<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Rawilk\LaravelBase\Contracts\Models\AuthenticatorApp;
use Rawilk\LaravelBase\Models\Role;
use Rawilk\LaravelBase\Policies\AuthenticatorAppPolicy;
use Rawilk\LaravelBase\Policies\RolePolicy;
use Rawilk\LaravelBase\Policies\WebauthnKeyPolicy;
use Rawilk\Webauthn\Contracts\WebauthnKey;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Role::class => RolePolicy::class,
        WebauthnKey::class => WebauthnKeyPolicy::class,
        AuthenticatorApp::class => AuthenticatorAppPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

//        $this->registerSuperAdmin();
    }

    protected function registerSuperAdmin(): void
    {
        // Implicitly grant the "Super Admin" role all permissions.
        // This works in the app by using gate-related functions like auth()->user()->can() and @can().
        Gate::after(function ($user) {
            if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
                return true;
            }

            return null;
        });
    }
}
