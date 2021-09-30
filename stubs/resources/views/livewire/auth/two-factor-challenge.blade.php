<div>
    <x-auth.authentication-form title="{{ __('laravel-base::users.two_factor.title') }}">
        <x-form wire:submit.prevent="login">
            <div x-data="{ recovery: false }"
                 x-on:otp-finish.window="$wire.login()"
                 class="space-y-6"
            >
                <p class="text-gray-600" x-show="! recovery">
                    {{ __('laravel-base::users.two_factor.app_code_description') }}
                </p>

                <p class="text-gray-600" x-show="recovery" x-cloak>
                    {{ __('laravel-base::users.two_factor.recovery_code_description') }}
                </p>

                <x-form-error name="two_factor" wire:key="two-factor-error">
                    @php($twoFactorError = $component->messages($errors)[0] ?? '')

                    <x-alert :type="\Rawilk\LaravelBase\Components\Alerts\Alert::ERROR">
                        <p>{!! $twoFactorError !!}</p>
                    </x-alert>
                </x-form-error>

                {{-- authenticator code --}}
                <x-form-group :label="false" name="code" x-show="! recovery" wire:key="two-factor-code">
                    <x-laravel-base::inputs.otp
                        name="code"
                        wire:model.defer="code"
                    />
                </x-form-group>

                {{-- recovery code --}}
                <x-form-group label="{{ __('Recovery Code') }}" name="recoveryCode" x-show="recovery" x-cloak wire:key="two-factor-recovery-code">
                    <x-input
                        wire:model.defer="recoveryCode"
                        x-bind:required="recovery"
                        name="recoveryCode"
                        x-ref="recoveryCode"
                    />
                </x-form-group>

                <div class="flex items-center justify-end">
                    <x-laravel-base::button.link
                        x-show="! recovery"
                        x-on:click.prevent="
                            recovery = true;
                            $wire.set('code', '');
                            $nextTick(() => { setTimeout(() => { $refs.recoveryCode.focus(); }, 50) });
                        "
                        class="text-xs"
                    >
                        {{ __('laravel-base::users.two_factor.use_recovery_code_button') }}
                    </x-laravel-base::button.link>

                    <x-laravel-base::button.link
                        x-show="recovery"
                        x-cloak
                        x-on:click.prevent="
                            recovery = false;
                            $wire.set('recoveryCode', '');
                            setTimeout(() => $dispatch('focus-otp'), 50);
                        "
                        class="text-xs"
                    >
                        {{ __('laravel-base::users.two_factor.use_auth_code_button') }}
                    </x-laravel-base::button.link>
                </div>
            </div>

            <div class="mt-6">
                <x-button variant="blue" type="submit" block wire:target="login">
                    {{ __('laravel-base::users.two_factor.verify_button') }}
                </x-button>
            </div>
        </x-form>
    </x-auth.authentication-form>
</div>
