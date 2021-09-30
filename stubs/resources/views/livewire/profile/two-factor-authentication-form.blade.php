<div>
    <x-card>
        <x-slot name="header">
            <h2>{{ __('laravel-base::users.profile.two_factor_title') }}</h2>
            <p class="text-sm text-cool-gray-500">
                {{ __('laravel-base::users.profile.two_factor_sub_title') }}
            </p>
        </x-slot>

        <h3 class="text-lg font-medium text-gray-900 flex items-center">
            @if ($this->enabled)
                <x-heroicon-o-check-circle class="h-6 w-6 text-green-600 mr-1" />
                <span>{{ __('laravel-base::users.profile.two_factor_enabled_status') }}</span>
            @else
                <x-heroicon-o-x-circle class="h-6 w-6 text-red-600 mr-1" />
                <span>{{ __('laravel-base::users.profile.two_factor_disabled_status') }}</span>
            @endif
        </h3>

        <div class="mt-3 max-w-xl text-sm text-gray-600">
            <p>
                {{ __('laravel-base::users.profile.two_factor_description') }}
            </p>
        </div>

        @if ($this->enabled)
            @includeWhen($showingQrCode, 'laravel-base::partials.two-factor.qr-code')

            @includeWhen($showingRecoveryCodes, 'laravel-base::partials.two-factor.recovery-codes')
        @endif

        <div class="mt-5">
            @unless ($this->enabled)
                {{-- enable trigger --}}
                <x-profile.two-factor-trigger variant="blue" action="enableTwoFactorAuthentication" :confirmEnabled="$this->mustConfirmPassword">
                    {{ __('laravel-base::users.profile.two_factor_triggers.enable') }}
                </x-profile.two-factor-trigger>
            @else
                @if ($showingRecoveryCodes)
                    {{-- regenerate recovery codes trigger --}}
                    <x-profile.two-factor-trigger variant="white" class="mr-3" action="regenerateRecoveryCodes" :confirmEnabled="$this->mustConfirmPassword">
                        {{ __('laravel-base::users.profile.two_factor_triggers.regenerate_recovery_codes') }}
                    </x-profile.two-factor-trigger>
                @else
                    {{-- show recovery codes trigger --}}
                    <x-profile.two-factor-trigger variant="white" class="mr-3" action="showRecoveryCodes" :confirmEnabled="$this->mustConfirmPassword">
                        {{ __('laravel-base::users.profile.two_factor_triggers.show_recovery_codes') }}
                    </x-profile.two-factor-trigger>
                @endif

                {{-- disable 2fa trigger --}}
                <x-profile.two-factor-trigger variant="red" action="disableTwoFactorAuthentication" :confirmEnabled="$this->mustConfirmPassword">
                    {{ __('laravel-base::users.profile.two_factor_triggers.disable') }}
                </x-profile.two-factor-trigger>
            @endunless
        </div>
    </x-card>
</div>
