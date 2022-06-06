<?php

namespace Rawilk\LaravelBase\Concerns;

use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;
use Rawilk\LaravelBase\Contracts\Auth\TwoFactorAuthenticationProvider;
use Rawilk\LaravelBase\LaravelBase;
use Rawilk\LaravelBase\Services\RecoveryCode;

/**
 * @property null|string $two_factor_recovery_codes
 * @mixin \Eloquent
 */
trait TwoFactorAuthenticatable
{
    public function recoveryCodes(): array
    {
        return json_decode(Crypt::decrypt($this->two_factor_recovery_codes), true);
    }

    public function replaceRecoveryCode(string $code): void
    {
        $this->forceFill([
            'two_factor_recovery_codes' => Crypt::encrypt(
                str_replace(
                    $code,
                    RecoveryCode::generate(),
                    Crypt::decrypt($this->two_factor_recovery_codes)
                )
            ),
        ])->save();
    }

    public function authenticatorApps(): HasMany
    {
        return $this->hasMany(config('laravel-base.authenticator_apps.model'));
    }
}
