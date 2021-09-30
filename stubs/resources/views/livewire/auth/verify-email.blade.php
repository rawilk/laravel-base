<div>
    <x-auth.authentication-form title="{{ __('Confirm Email') }}">
        <div class="mb-4 text-sm text-gray-600">
            {{ __('Thanks for signing up! Before getting started, we need you to verify your email address by clicking on the link we just emailed you. If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('resent'))
            <x-alert :type="\Rawilk\LaravelBase\Components\Alerts\Alert::SUCCESS" dismiss wire:key="resent-{{ time() }}">
                <p>
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </p>
            </x-alert>
        @endif

        <x-form class="mt-4" wire:submit.prevent="resend">
            <div>
                <x-button type="submit" variant="blue" block wire:target="resend">
                    {{ __('Resend Verification Email') }}
                </x-button>
            </div>
        </x-form>

        <x-form action="{{ route('logout') }}">
            <div class="py-4 text-center">
                <x-laravel-base::button.link type="submit" dark>
                    <span>{{ __('labels.user.logout_button') }}</span>
                </x-laravel-base::button.link>
            </div>
        </x-form>
    </x-auth.authentication-form>
</div>
