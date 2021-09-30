<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase;

use Illuminate\Support\Facades\Config;

class Features
{
    public static function enabled(string $feature): bool
    {
        return in_array($feature, Config::get('laravel-base.features', []), true);
    }

    public static function optionEnabled(string $feature, string $option): bool
    {
        return static::enabled($feature)
            && Config::get("laravel-base-options.{$feature}.{$option}") === true;
    }

    /*
     * Determine if the application is allowing avatar photo uploads.
     */
    public static function managesAvatars(): bool
    {
        return static::enabled(static::avatars());
    }

    /*
     * Determine if the application can update a user's profile information.
     */
    public static function canUpdateProfileInformation(): bool
    {
        return static::enabled(static::updateProfileInformation());
    }

    /*
     * Determine if the application can manage two factor authentication.
     */
    public static function canManageTwoFactorAuthentication(): bool
    {
        return static::enabled(static::twoFactorAuthentication());
    }

    /*
     * Determine if the application is using any account deletion features.
     */
    public static function hasAccountDeletionFeatures(): bool
    {
        return static::enabled(static::accountDeletion());
    }

    /*
     * Determine if the application will allow new users to register.
     */
    public static function usersCanRegister(): bool
    {
        return static::enabled(static::registration());
    }

    /*
     * Enable the registration feature.
     */
    public static function registration(): string
    {
        return 'registration';
    }

    /*
     * Enable the password reset feature.
     */
    public static function resetPasswords(): string
    {
        return 'reset-passwords';
    }

    /*
     * Enable the email verification feature.
     */
    public static function emailVerification(): string
    {
        return 'email-verification';
    }

    /*
     * Enable the account deletion feature.
     */
    public static function accountDeletion(): string
    {
        return 'account-deletion';
    }

    /*
     * Enable the update profile information feature.
     */
    public static function updateProfileInformation(): string
    {
        return 'update-profile-information';
    }

    /*
     * Enable the update passwords feature.
     */
    public static function updatePasswords(): string
    {
        return 'update-passwords';
    }

    /*
     * Enable the avatar photo upload feature.
     */
    public static function avatars(): string
    {
        return 'avatars';
    }

    /*
     * Enable the two factor authentication feature.
     */
    public static function twoFactorAuthentication(array $options = []): string
    {
        if (count($options)) {
            // Note: Cannot use the Config facade here otherwise it breaks the console
            config(['laravel-base-options.two-factor-authentication' => $options]);
        }

        return 'two-factor-authentication';
    }
}
