<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\View\View;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Rawilk\LaravelBase\Actions\Auth\AttemptToAuthenticate;
use Rawilk\LaravelBase\Actions\Auth\EnsureLoginIsNotThrottled;
use Rawilk\LaravelBase\Actions\Auth\EnsureUserAccountIsActive;
use Rawilk\LaravelBase\Actions\Auth\PrepareAuthenticatedSession;
use Rawilk\LaravelBase\Actions\Auth\RedirectIfTwoFactorAuthenticatable;
use Rawilk\LaravelBase\Contracts\Auth\FailedTwoFactorLoginResponse;
use Rawilk\LaravelBase\Contracts\Auth\TwoFactorChallengeViewResponse;
use Rawilk\LaravelBase\Contracts\Auth\TwoFactorLoginResponse;
use Rawilk\LaravelBase\Contracts\Models\AuthenticatorApp;
use Rawilk\LaravelBase\Events\Auth\RecoveryCodeReplaced;
use Rawilk\LaravelBase\Features;
use Rawilk\LaravelBase\Http\Livewire\Concerns\ThrottlesAuthenticationAttempts;
use Rawilk\LaravelBase\Http\Requests\TwoFactorLoginRequest;
use Rawilk\LaravelBase\LaravelBase;
use Rawilk\Webauthn\Actions\PrepareAssertionData;
use Rawilk\Webauthn\Contracts\WebauthnKey;
use Rawilk\Webauthn\Facades\Webauthn;

class TwoFactorLogin extends Component
{
    use ThrottlesAuthenticationAttempts;

    public string $totp = '';
    public string $recoveryCode = '';
    public array $challengeOptions = [];
    public string $currentChallengeType = 'backup_code';

    /*
     * Public WebAuthn assertion key for when
     * a user has at least one key registered.
     */
    public string $publicKey = '';
    public $keyData;

    public function guard(): StatefulGuard
    {
        return Auth::guard();
    }

    public function login(TwoFactorLoginRequest $request)
    {
        try {
            $this->rateLimit(static::$maxAttempts, static::$decaySeconds);
        } catch (TooManyRequestsException $e) {
            throw ValidationException::withMessages([
                'two_factor' => [
                    __('auth.throttle', [
                        'seconds' => $e->secondsUntilAvailable,
                        'minutes' => $e->minutesUntilAvailable,
                    ]),
                ],
            ]);
        }

        $this->resetErrorBag();

        $request->merge([
            'totp' => $this->totp,
            'recoveryCode' => $this->recoveryCode,
        ]);

        $request->validate($request->rules());

        $user = $request->challengedUser();

        switch ($this->currentChallengeType) {
            case 'backup_code':
                $code = $request->validRecoveryCode();
                if (! $code) {
                    return app(FailedTwoFactorLoginResponse::class)->toResponse($request);
                }

                $user->replaceRecoveryCode($code);

                event(new RecoveryCodeReplaced($user, $code));

                break;
            case 'totp':
                if (! $request->hasValidTotpCode()) {
                    return app(FailedTwoFactorLoginResponse::class)->toResponse($request);
                }

                break;
            case 'key':
                // WebAuthn package will update the last_used_at timestamp of the key.
                $valid = Webauthn::validateAssertion($request->challengedUser(), Arr::only((array) $this->keyData, [
                    'id',
                    'rawId',
                    'response',
                    'type',
                ]));

                if (! $valid) {
                    return app(FailedTwoFactorLoginResponse::class)->toResponse($request);
                }

                break;
            default:
                return app(FailedTwoFactorLoginResponse::class)->toResponse($request);
        }

        $this->guard()->login($user, $request->remember());

        $request->merge([
            LaravelBase::username() => $user->{LaravelBase::username()},
        ]);

        return $this->loginPipeline($request)
            ->then(fn () => app(TwoFactorLoginResponse::class)->toResponse($request));
    }

    public function mount(TwoFactorLoginRequest $request): void
    {
        if (! $request->hasChallengedUser()) {
            redirect()->route('login');
        }

        $challengeOptions = ['backup_code'];
        $userId = session('login.id');

        if (Features::canManageTwoFactorAuthentication() && app(AuthenticatorApp::class)::where('user_id', $userId)->exists()) {
            $challengeOptions[] = 'totp';
            $this->currentChallengeType = 'totp';
        }

        if (Features::canManageWebauthnAuthentication() && app(WebauthnKey::class)::where('user_id', $userId)->exists()) {
            $challengeOptions[] = 'key';
            $this->currentChallengeType = 'key';

            $this->publicKey = json_encode(app(PrepareAssertionData::class)($request->challengedUser()));
        }

        $this->challengeOptions = $challengeOptions;
    }

    public function canTotp(): bool
    {
        return in_array('totp', $this->challengeOptions, true);
    }

    public function canSecurityKey(): bool
    {
        return in_array('key', $this->challengeOptions, true);
    }

    public function challengeOptionIcon(string $type): string
    {
        return match ($type) {
            'backup_code' => 'css-lock',
            'totp' => 'css-smartphone',
            'key' => 'css-usb',
        };
    }

    public function challengeOptionName(string $type): string
    {
        return match ($type) {
            'backup_code' => __('laravel-base::2fa.challenge.types.backup_code_name'),
            'totp' => __('laravel-base::2fa.challenge.types.totp_name'),
            'key' => __('laravel-base::2fa.challenge.types.key_name'),
        };
    }

    public function render(): View
    {
        return app(TwoFactorChallengeViewResponse::class)->toResponse(request());
    }

    protected function loginPipeline($request): Pipeline
    {
        if (LaravelBase::$authenticateThroughCallback) {
            return app(Pipeline::class)
                ->send($request)
                ->through(
                    $this->filterPipes(call_user_func(LaravelBase::$authenticateThroughCallback, $request))
                );
        }

        return app(Pipeline::class)
            ->send($request)
            ->through([
                EnsureUserAccountIsActive::class,
                PrepareAuthenticatedSession::class,
            ]);
    }

    /**
     * Certain actions do not (and should not) need to be performed on this step
     * of the authentication process, so we'll filter them out here.
     *
     * @param array $pipes
     * @return array
     */
    private function filterPipes(array $pipes): array
    {
        return array_filter($pipes, function ($pipe) {
            return ! is_null($pipe)
                && ! in_array($pipe, [
                    EnsureLoginIsNotThrottled::class,
                    RedirectIfTwoFactorAuthenticatable::class,
                    AttemptToAuthenticate::class,
                ], true);
        });
    }

    protected function getRateLimitKey(): string
    {
        return 'two_factor_challenge|' . request()->ip() . '|' . request()->session()->get('login.id');
    }
}
