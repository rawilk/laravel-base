<div class="mt-4 max-w-xl text-sm text-gray-600">
    <p class="font-semibold">
        {{ __('laravel-base::users.profile.two_factor_setup_instructions') }}
    </p>
</div>

<div class="mt-4">
    {!! $this->user->twoFactorQrCodeSvg() !!}
</div>

<div class="mt-4 max-w-xl text-sm text-gray-600">
    <p>
        {{ __('laravel-base::users.profile.two_factor_setup_help') }}
    </p>

    <div class="relative mt-2 rounded-md px-3 py-3 relative bg-gray-100 text-sm font-mono">
        <span>{{ decrypt($this->user->two_factor_secret) }}</span>

        <div class="absolute top-0 right-1 pr-1 pt-1">
            <x-laravel-base::misc.copy-to-clipboard class="border-none" text="{{ decrypt($this->user->two_factor_secret) }}" />
        </div>
    </div>
</div>
