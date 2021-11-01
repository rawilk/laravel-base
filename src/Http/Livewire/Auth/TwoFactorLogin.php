<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\View\View;
use Illuminate\Pipeline\Pipeline;
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
use Rawilk\LaravelBase\Events\Auth\RecoveryCodeReplaced;
use Rawilk\LaravelBase\Http\Livewire\Concerns\ThrottlesAuthenticationAttempts;
use Rawilk\LaravelBase\Http\Requests\TwoFactorLoginRequest;
use Rawilk\LaravelBase\LaravelBase;

class TwoFactorLogin extends Component
{
    use ThrottlesAuthenticationAttempts;

    public string $code = '';
    public string $recoveryCode = '';

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
            'code' => $this->code,
            'recoveryCode' => $this->recoveryCode,
        ]);

        $request->validate($request->rules());

        $user = $request->challengedUser();

        if ($code = $request->validRecoveryCode()) {
            $user->replaceRecoveryCode($code);

            event(new RecoveryCodeReplaced($user, $code));
        } elseif (! $request->hasValidCode()) {
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
