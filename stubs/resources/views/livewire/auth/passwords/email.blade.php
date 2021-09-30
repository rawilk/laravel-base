<div>
    <x-auth.authentication-form title="{{ __('Reset Password') }}">
        <x-slot name="subTitle">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new password.') }}
        </x-slot>

        @include('layouts.partials.session-alert')

        @unless ($emailSent)
            <x-form wire:submit.prevent="sendPasswordResetLink">
                <x-form-group label="{{ __('Email address') }}" name="email">
                    <x-email
                        wire:model.defer="email"
                        name="email"
                        autocomplete="email"
                        autofocus
                        required
                    />
                </x-form-group>

                <div class="mt-6">
                    <x-button type="submit" variant="blue" block wire:target="sendPasswordResetLink">
                        {{ __('Send password reset link') }}
                    </x-button>
                </div>
            </x-form>
        @endunless

        <div class="mt-4">
            @include('livewire.auth.partials.back-to-login')
        </div>
    </x-auth.authentication-form>
</div>
