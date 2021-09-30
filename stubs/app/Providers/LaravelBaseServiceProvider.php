<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\Auth\RegisterUserAction;
use App\Actions\Auth\ResetUserPasswordAction;
use App\Actions\LaravelBase\DeleteUserAction;
use App\Actions\LaravelBase\UpdatePasswordAction;
use App\Actions\LaravelBase\UpdateUserProfileInformationAction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Rawilk\LaravelBase\LaravelBase;

final class LaravelBaseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // LaravelBase::findAppTimezoneUsing(function () {
        //     return config('app.timezone');
        // });

        $this->registerViews();
        $this->registerBindings();
        $this->setDefaultPasswordRules();
    }

    private function registerBindings(): void
    {
        LaravelBase::registerUsersUsing(RegisterUserAction::class);
        LaravelBase::resetUserPasswordsUsing(ResetUserPasswordAction::class);
        LaravelBase::updateUserProfileInformationUsing(UpdateUserProfileInformationAction::class);
        LaravelBase::updateUserPasswordsUsing(UpdatePasswordAction::class);
        LaravelBase::deleteUsersUsing(DeleteUserAction::class);
    }

    private function registerViews(): void
    {
        LaravelBase::loginView(function () {
            return view('livewire.auth.login')
                ->layout('layouts.auth.base', [
                    'title' => __('Sign in to your account'),
                ]);
        });

        LaravelBase::registerView(function () {
            return view('livewire.auth.register')
                ->layout('layouts.auth.base', [
                    'title' => __('Create a new account'),
                ]);
        });

        // LaravelBase::verifyEmailView(function () {
        //     return view('livewire.auth.verify-email')
        //         ->layout('layouts.auth.base', [
        //             'title' => __('Confirm Email'),
        //         ]);
        // });

        LaravelBase::requestPasswordResetLinkView(function () {
            return view('livewire.auth.passwords.email')
                ->layout('layouts.auth.base', [
                    'title' => __('Reset Password'),
                ]);
        });

        LaravelBase::resetPasswordView(function () {
            return view('livewire.auth.passwords.reset')
                ->layout('layouts.auth.base', [
                    'title' => __('Reset Password'),
                ]);
        });

        LaravelBase::twoFactorChallengeView(function () {
            return view('livewire.auth.two-factor-challenge')
                ->layout('layouts.auth.base', [
                    'title' => __('laravel-base::users.two_factor.title'),
                ]);
        });

        LaravelBase::confirmPasswordView(function () {
            return view('livewire.auth.confirm-password')
                ->layout('layouts.auth.base', [
                    'title' => __('Confirm Password'),
                ]);
        });
    }

    private function setDefaultPasswordRules(): void
    {
        Password::defaults(function () {
            return Password::min(6)
                ->when($this->app->isProduction(), function (Password $rule) {
                    return $rule->uncompromised();
                });
        });
    }
}
