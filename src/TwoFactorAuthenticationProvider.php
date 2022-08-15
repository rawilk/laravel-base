<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase;

use PragmaRX\Google2FA\Google2FA;
use Rawilk\LaravelBase\Contracts\Auth\TwoFactorAuthenticationProvider as TwoFactorAuthenticationProviderContract;

class TwoFactorAuthenticationProvider implements TwoFactorAuthenticationProviderContract
{
    /**
     * @param  \PragmaRX\Google2FA\Google2FA  $engine The underlying library providing two-factor authentication helper services
     */
    public function __construct(protected Google2FA $engine)
    {
    }

    public function generateSecretKey(): string
    {
        return $this->engine->generateSecretKey();
    }

    public function qrCodeUrl(string $companyName, string $companyEmail, string $secret): string
    {
        return $this->engine->getQRCodeUrl($companyName, $companyEmail, $secret);
    }

    public function verify(string $secret, string $code): bool
    {
        return (bool) $this->engine->verifyKey($secret, $code);
    }
}
