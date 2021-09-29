<?php

namespace Rawilk\LaravelBase\Concerns;

use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Crypt;
use Rawilk\LaravelBase\Contracts\Auth\TwoFactorAuthenticationProvider;
use Rawilk\LaravelBase\LaravelBase;
use Rawilk\LaravelBase\Services\RecoveryCode;

/**
 * @property null|string $two_factor_recovery_codes
 * @property null|string $two_factor_secret
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

    public function twoFactorQrCodeSvg(): string
    {
        $svg = (new Writer(
            new ImageRenderer(
                new RendererStyle(192, 0, null, null, Fill::uniformColor(new Rgb(255, 255, 255), new Rgb(45, 55, 72))),
                new SvgImageBackEnd
            )
        ))->writeString($this->twoFactorQrCodeUrl());

        return trim(substr($svg, strpos($svg, PHP_EOL) + 1));
    }

    public function twoFactorQrCodeUrl(): string
    {
        return app(TwoFactorAuthenticationProvider::class)->qrCodeUrl(
            appName(),
            $this->{LaravelBase::username()},
            Crypt::decrypt($this->two_factor_secret),
        );
    }
}
