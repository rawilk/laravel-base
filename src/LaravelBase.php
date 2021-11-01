<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase;

use Illuminate\Support\Facades\Config;
use Rawilk\LaravelBase\Contracts\Auth\ConfirmPasswordViewResponse;
use Rawilk\LaravelBase\Contracts\Auth\LoginViewResponse;
use Rawilk\LaravelBase\Contracts\Auth\RegistersNewUsers;
use Rawilk\LaravelBase\Contracts\Auth\RegisterViewResponse;
use Rawilk\LaravelBase\Contracts\Auth\RequestPasswordResetLinkViewResponse;
use Rawilk\LaravelBase\Contracts\Auth\ResetPasswordViewResponse;
use Rawilk\LaravelBase\Contracts\Auth\ResetsUserPasswords;
use Rawilk\LaravelBase\Contracts\Auth\TwoFactorChallengeViewResponse;
use Rawilk\LaravelBase\Contracts\Auth\VerifyEmailViewResponse;
use Rawilk\LaravelBase\Contracts\Profile\DeletesUsers;
use Rawilk\LaravelBase\Contracts\Profile\UpdatesUserPasswords;
use Rawilk\LaravelBase\Contracts\Profile\UpdatesUserProfileInformation;
use Rawilk\LaravelBase\Http\Responses\SimpleViewResponse;
use Rawilk\LaravelBase\Services\Routing;

class LaravelBase
{
    /**
     * The callback that is responsible for retrieving the configured app timezone.
     *
     * @var callable|null
     */
    public static $findAppTimezoneUsingCallback;

    /**
     * The callback that is responsible for retrieving the authenticated user's timezone.
     *
     * @var callable|null
     */
    public static $findUserTimezoneUsingCallback;

    /**
     * The callback that is responsible for validating authentication credentials, if applicable.
     *
     * @var callable|null
     */
    public static $authenticateUsingCallback;

    /**
     * The callback that is responsible for building the authentication pipeline array, if applicable.
     *
     * @var callable|null
     */
    public static $authenticateThroughCallback;

    /**
     * The callback that is responsible for confirming user passwords.
     *
     * @var callable|null
     */
    public static $confirmPasswordsUsingCallback;

    /*
     * Indicates if LaravelBase routes will be registered.
     */
    public static bool $registersRoutes = true;

    /**
     * Get the username used for authentication.
     */
    public static function username(): string
    {
        return Config::get('laravel-base.username', 'email');
    }

    /**
     * Get the name of the email address request variable / field.
     */
    public static function email(): string
    {
        return Config::get('laravel-base.email', 'email');
    }

    /*
     * Get the completion redirect path for a specific feature.
     */
    public static function redirects(string $redirect, null|string $default = null): string
    {
        return (string) (Config::get("laravel-base.redirects.{$redirect}") ?? $default ?? Routing::home());
    }

    /**
     * Register a callback that is responsible for retrieving the configured app timezone.
     *
     * @param callable $callback
     */
    public static function findAppTimezoneUsing(callable $callback): void
    {
        static::$findAppTimezoneUsingCallback = $callback;
    }

    /**
     * Register a callback that is responsible for retrieving the authenticated user's timezone.
     *
     * @param callable $callback
     */
    public static function findUserTimezoneUsing(callable $callback): void
    {
        static::$findUserTimezoneUsingCallback = $callback;
    }

    /**
     * Register a callback that is responsible for validating incoming authentication credentials.
     *
     * @param callable $callback
     */
    public static function authenticateUsing(callable $callback): void
    {
        static::$authenticateUsingCallback = $callback;
    }

    /**
     * Register a callback that is responsible for building the authentication pipeline array.
     *
     * @param callable $callback
     */
    public static function authenticateThrough(callable $callback): void
    {
        static::$authenticateThroughCallback = $callback;
    }

    /**
     * Specify which view should be used as the login view.
     *
     * @param callable|string $view
     */
    public static function loginView(callable|string $view): void
    {
        app()->singleton(LoginViewResponse::class, fn () => new SimpleViewResponse($view));
    }

    /**
     * Specify which view should be used as the register view.
     *
     * @param callable|string $view
     */
    public static function registerView(callable|string $view): void
    {
        app()->singleton(RegisterViewResponse::class, fn () => new SimpleViewResponse($view));
    }

    /**
     * Specify which view should be used as the email verification prompt.
     *
     * @param callable|string $view
     */
    public static function verifyEmailView(callable|string $view): void
    {
        app()->singleton(VerifyEmailViewResponse::class, fn () => new SimpleViewResponse($view));
    }

    /**
     * Specify which view should be used as teh request password reset link view.
     *
     * @param callable|string $view
     */
    public static function requestPasswordResetLinkView(callable|string $view): void
    {
        app()->singleton(RequestPasswordResetLinkViewResponse::class, fn () => new SimpleViewResponse($view));
    }

    /**
     * Specify which view should be used as the new password view.
     *
     * @param callable|string $view
     */
    public static function resetPasswordView(callable|string $view): void
    {
        app()->singleton(ResetPasswordViewResponse::class, fn () => new SimpleViewResponse($view));
    }

    /**
     * Specify which view should be used as the two factor authentication challenge view.
     *
     * @param callable|string $view
     */
    public static function twoFactorChallengeView(callable|string $view): void
    {
        app()->singleton(TwoFactorChallengeViewResponse::class, fn () => new SimpleViewResponse($view));
    }

    /**
     * Specify which view should be used as the password confirmation prompt.
     *
     * @param callable|string $view
     */
    public static function confirmPasswordView(callable|string $view): void
    {
        app()->singleton(ConfirmPasswordViewResponse::class, fn () => new SimpleViewResponse($view));
    }

    /**
     * Register a class that should be used to register new users.
     *
     * @param string $class
     */
    public static function registerUsersUsing(string $class): void
    {
        app()->singleton(RegistersNewUsers::class, $class);
    }

    /**
     * Register a class that should be used to reset user passwords.
     *
     * @param string $class
     */
    public static function resetUserPasswordsUsing(string $class): void
    {
        app()->singleton(ResetsUserPasswords::class, $class);
    }

    /**
     * Register a class that should be used to update user profile information.
     *
     * @param string $class
     */
    public static function updateUserProfileInformationUsing(string $class): void
    {
        app()->singleton(UpdatesUserProfileInformation::class, $class);
    }

    /**
     * Register a class that should be used to update user passwords.
     *
     * @param string $class
     */
    public static function updateUserPasswordsUsing(string $class): void
    {
        app()->singleton(UpdatesUserPasswords::class, $class);
    }

    /**
     * Register a class that should be used to delete users.
     *
     * @param string $class
     */
    public static function deleteUsersUsing(string $class): void
    {
        app()->singleton(DeletesUsers::class, $class);
    }

    /**
     * Register a callback that is responsible for confirming existing user passwords as valid.
     *
     * @param callable $callback
     */
    public static function confirmPasswordsUsing(callable $callback): void
    {
        static::$confirmPasswordsUsingCallback = $callback;
    }

    public static function configuredView(string $feature, string $default = ''): string
    {
        return (string) Config::get("laravel-base.views.{$feature}", $default);
    }

    public static function adminViewLayout(): string
    {
        return (string) Config::get('laravel-base.admin_view_layout', 'layouts.app.base');
    }

    /**
     * Configure LaravelBase to not register its routes.
     *
     * @return static
     */
    public static function ignoreRoutes(): self
    {
        static::$registersRoutes = false;

        return new static;
    }
}
