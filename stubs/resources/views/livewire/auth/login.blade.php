<div>
    <x-auth.authentication-form title="{{ __('Sign in to your account') }}">
        @if (\Rawilk\LaravelBase\Features::usersCanRegister())
            <x-slot name="subTitle">
                Or <x-link href="{!! route('register') !!}">{{ __('sign up for a new account') }}</x-link>
            </x-slot>
        @endif

        <x-form wire:submit.prevent="login">
            {{-- email --}}
            <x-form-group label="{{ __('Email address') }}" name="email">
                <x-email
                    wire:model.defer="email"
                    name="email"
                    autofocus
                    required
                />
            </x-form-group>

            {{-- password --}}
            <x-form-group label="{{ __('Password') }}" name="password">
                <x-password
                    wire:model.defer="password"
                    name="password"
                    required
                />
            </x-form-group>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <x-checkbox wire:model.defer="remember" name="remember">
                        {{ __('Remember me') }}
                    </x-checkbox>
                </div>

                @if (Route::has('password.request'))
                    <div class="text-sm leading-5">
                        <x-link href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </x-link>
                    </div>
                @endif
            </div>

            <div class="mt-6">
                <x-button variant="blue" type="submit" block wire:target="login">
                    {{ __('Sign in') }}
                </x-button>
            </div>
        </x-form>
    </x-auth.authentication-form>
</div>
