<div>
    <x-card>
        <x-slot name="header">
            <h2>{{ __('Update Password') }}</h2>
            <p class="text-sm text-cool-gray-500">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
        </x-slot>

        <x-form wire:submit.prevent="updatePassword" id="update-password-form">
            <div>
                {{-- current passwrod --}}
                <x-form-group label="{{ __('Current password') }}" name="current_password" inline>
                    <x-password
                        wire:model.defer="state.current_password"
                        name="current_password"
                        required
                        autocomplete="current-password"
                    />

                    @if (\Rawilk\LaravelBase\Features::enabled(\Rawilk\LaravelBase\Features::resetPasswords()))
                        <x-slot name="helpText">
                            <div class="text-right">
                                <x-link href="{!! route('password.request') !!}" class="text-xs">
                                    {{ __('Forgot your password?') }}
                                </x-link>
                            </div>
                        </x-slot>
                    @endif
                </x-form-group>

                {{-- new password --}}
                <x-form-group label="{{ __('New password') }}" name="password" inline>
                    <x-password
                        wire:model.defer="state.password"
                        name="password"
                        required
                        autocomplete="new-password"
                    />
                </x-form-group>

                {{-- password confirmation --}}
                <x-form-group label="{{ __('Confirm password') }}" name="password_confirmation" inline>
                    <x-password
                        wire:model.defer="state.password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                </x-form-group>
            </div>
        </x-form>

        <x-slot name="footer">
            <div class="flex justify-end items-center space-x-4">
                <x-action-message on="saved" />

                <x-button type="submit" variant="blue" form="update-password-form" wire:target="updatePassword">
                    <span>{{ __('Save') }}</span>
                    <x-heroicon-s-check />
                </x-button>
            </div>
        </x-slot>
    </x-card>
</div>
