<div class="mt-4 max-w-xl text-sm text-gray-600">
    <p class="font-semibold">
        {{ __('laravel-base::users.profile.two_factor_recovery_codes_description') }}
    </p>
</div>

<div class="flex justify-between max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg relative">
    <div class="grid gap-1 flex-1">
        @foreach ($this->user->recoveryCodes() as $code)
            <div>{{ $code }}</div>
        @endforeach
    </div>

    <div class="flex-shrink-0">
        <x-laravel-base::misc.copy-to-clipboard :text="$this->user->recoveryCodes()" />
    </div>
</div>
