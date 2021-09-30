<div>
    <x-auth.authentication-form title="{{ __('Reset Password') }}">
        @include('livewire.auth.partials.validation-error', ['inputName' => 'token'])

        <x-form wire:submit.prevent="resetPassword">
            @if ($needsEmail)
                <x-form-group label="{{ __('Email address') }}" name="email">
                    <x-email
                        wire:model.defer="email"
                        name="email"
                        autocomplete="email"
                        autofocus
                        required
                    />
                </x-form-group>
            @else
                @include('livewire.auth.partials.validation-error', ['inputName' => 'email'])
            @endif

            <x-form-group label="{{ __('New password') }}" name="password">
                <x-password
                    wire:model.defer="password"
                    name="password"
                    required
                    :autofocus="$needsEmail ? null : 'autofocus'"
                />
            </x-form-group>

            <div class="mt-6">
                <x-button type="submit" variant="blue" block wire:target="resetPassword">
                    {{ __('Reset Password') }}
                </x-button>
            </div>
        </x-form>

        <div class="mt-4">
            @include('livewire.auth.partials.back-to-login')
        </div>
    </x-auth.authentication-form>
</div>
