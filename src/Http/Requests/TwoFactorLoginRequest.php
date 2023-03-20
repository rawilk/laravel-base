<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Requests;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Crypt;
use Rawilk\LaravelBase\Contracts\Auth\FailedTwoFactorLoginResponse;
use Rawilk\LaravelBase\Contracts\Auth\TwoFactorAuthenticationProvider;
use Rawilk\LaravelBase\Contracts\Models\AuthenticatorApp;
use Rawilk\LaravelBase\Events\Auth\TwoFactorAuthenticationAppUsed;

/**
 * @property null|string $totp
 * @property null|string $recoveryCode
 */
class TwoFactorLoginRequest extends FormRequest
{
    /**
     * The user attempting the two factor challenge.
     *
     * @var mixed
     */
    protected $challengedUser;

    /**
     * Indicates if the user wished to be remembered after login.
     *
     * @var bool
     */
    protected $remember;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'totp' => ['nullable', 'string'],
            'recovery_code' => ['nullable', 'string'],
        ];
    }

    /**
     * Determine if there is a challenged user in the current session.
     */
    public function hasChallengedUser(): bool
    {
        $model = app(StatefulGuard::class)->getProvider()->getModel();

        return $this->session()->has('login.id')
            && $model::find($this->session()->get('login.id'));
    }

    /**
     * Get the user that is attempting the two factor challenge.
     *
     * @return mixed
     */
    public function challengedUser()
    {
        if ($this->challengedUser) {
            return $this->challengedUser;
        }

        $model = app(StatefulGuard::class)->getProvider()->getModel();

        if (
            ! $this->session()->has('login.id')
                || ! $user = $model::find($this->session()->get('login.id'))
        ) {
            throw new HttpResponseException(
                app(FailedTwoFactorLoginResponse::class)->toResponse($this)
            );
        }

        return $this->challengedUser = $user;
    }

    /**
     * Get the valid recovery code if one exists on the request.
     */
    public function validRecoveryCode(): ?string
    {
        if (! $this->recoveryCode) {
            return null;
        }

        return collect($this->challengedUser()->recoveryCodes())
            ->first(fn ($code) => hash_equals($this->recoveryCode, $code) ? $code : null);
    }

    /**
     * Determine if the request has a valid totp code.
     */
    public function hasValidTotpCode(): bool
    {
        if (! $this->totp) {
            return false;
        }

        $authenticatorApps = app(AuthenticatorApp::class)::query()
            ->where('user_id', $this->challengedUser()->getAuthIdentifier())
            ->get(['id', 'secret']);

        foreach ($authenticatorApps as $authenticatorApp) {
            if (app(TwoFactorAuthenticationProvider::class)->verify(Crypt::decrypt($authenticatorApp->secret), $this->totp)) {
                TwoFactorAuthenticationAppUsed::dispatch($this->challengedUser(), $authenticatorApp);

                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the user wanted to be remembered after login.
     */
    public function remember(): bool
    {
        if (! $this->remember) {
            $this->remember = $this->session()->pull('login.remember', false);
        }

        return $this->remember;
    }
}
