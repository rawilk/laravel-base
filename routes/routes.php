<?php

use Illuminate\Support\Facades\Route;
use Rawilk\LaravelBase\Features;
use Rawilk\LaravelBase\Http\Controllers;
use Rawilk\LaravelBase\Http\Livewire;

Route::group(['middleware' => config('laravel-base.middleware', ['web'])], function () {
    Route::middleware(['guest:' . config('laravel-base.guard')])->group(function () {
        // Authentication...
        Route::get('/login', config('laravel-base.livewire.login', Livewire\Auth\Login::class))
            ->name('login');

        // Registration...
        if (Features::usersCanRegister()) {
            Route::get('/register', config('laravel-base.livewire.register', Livewire\Auth\Register::class))
                ->name('register');
        }
    });

    // Logout...
    Route::post('/logout', Controllers\Auth\LogoutController::class)
        ->middleware(['auth:' . config('laravel-base.guard')])
        ->name('logout');

    // Password Reset...
    if (Features::enabled(Features::resetPasswords())) {
        Route::get('/password/reset', Livewire\Auth\Passwords\Email::class)->name('password.request');
        Route::get('/password/reset/{token}', Livewire\Auth\Passwords\Reset::class)->name('password.reset');
    }

    // Email Verification...
    if (Features::enabled(Features::emailVerification())) {
        $verificationLimiter = config('laravel-base.limiters.verification', '6,1');

        Route::get('/email/verify', Livewire\Auth\Verify::class)
            ->middleware(['auth:' . config('laravel-base.guard')])
            ->name('verification.notice');

        Route::get('/email/verify/{id}/{hash}', Controllers\Auth\VerifyEmailController::class)
            ->middleware(['auth:' . config('laravel-base.guard'), 'signed', 'throttle:' . $verificationLimiter])
            ->name('verification.verify');
    }

    // Password Confirmation...
    Route::get('/user/confirm-password', Livewire\Auth\ConfirmPassword::class)
        ->middleware(['auth:' . config('laravel-base.guard')])
        ->name('password.confirm');

    // Two Factor Authentication...
    if (Features::enabled(Features::twoFactorAuthentication())) {
        Route::get('/login/two-factor-challenge', Livewire\Auth\TwoFactorLogin::class)
            ->middleware(['guest:' . config('laravel-base.guard')])
            ->name('two-factor.login');
    }

    Route::middleware(['auth:' . config('laravel-base.guard')])->group(function () {
        // User & Profile...
        Route::get('/user/profile', [Controllers\UserProfileController::class, 'show'])
            ->name('profile.show');
        Route::get('/user/profile/authentication', [Controllers\UserProfileController::class, 'authentication'])
            ->name('profile.authentication');
    });
});
