<?php

use App\Enums\PermissionEnum;
use Illuminate\Support\Facades\Route;
use Rawilk\LaravelBase\Features;
use Rawilk\LaravelBase\Http\Controllers;
use Rawilk\LaravelBase\Http\Livewire;

Route::group(
    ['middleware' => config('laravel-base.admin_middleware', ['web', 'auth', \Rawilk\LaravelBase\Http\Middleware\EnsureActiveUserMiddleware::class])],
    function () {
        // Users...
        if (Features::managesUsers()) {
            // Impersonation...
            Route::post('/impersonate', [Controllers\Auth\ImpersonationController::class, 'impersonate'])->name('impersonate');
            Route::delete('/impersonate', [Controllers\Auth\ImpersonationController::class, 'stopImpersonating'])->name('impersonate.leave');

            Route::prefix('users')
                ->as('users.')
                ->group(function () {
                    Route::get('/', [Controllers\Admin\UsersController::class, 'index'])
                        ->name('index');
                    Route::get('/create', [Controllers\Admin\UsersController::class, 'create'])
                        ->middleware('permission:' . PermissionEnum::USERS_CREATE->value)
                        ->name('create');

                    Route::prefix('/{user}')->group(function () {
                        Route::get('/', [Controllers\Admin\UsersController::class, 'edit'])
                            ->middleware('can:edit,user')
                            ->name('edit');
                        Route::get('/abilities', [Controllers\Admin\UsersController::class, 'abilities'])
                            ->middleware('can:edit,user')
                            ->name('edit.abilities');
                    });
                });
        }

        // Roles...
        if (Features::managesRoles()) {
            Route::prefix('roles')
                ->as('roles.')
                ->group(function () {
                    Route::get('/', config('laravel-base.livewire.roles.index', Livewire\Roles\Index::class))
                        ->middleware('permission:' . PermissionEnum::ROLES_CREATE->value . '|' . PermissionEnum::ROLES_EDIT->value . '|' . PermissionEnum::ROLES_DELETE->value)
                        ->name('index');
                    Route::get('/create', config('laravel-base.livewire.roles.create', Livewire\Roles\Create::class))
                        ->middleware('permission:' . PermissionEnum::ROLES_CREATE->value)
                        ->name('create');
                    Route::get('/{role}/edit', config('laravel-base.livewire.roles.edit', Livewire\Roles\Edit::class))
                        ->middleware('can:edit,role')
                        ->name('edit');
                });
        }
    });
