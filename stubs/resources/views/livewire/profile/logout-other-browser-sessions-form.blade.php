<div>
    <x-card>
        <x-slot name="header">
            <h2>{{ __('Browser Sessions') }}</h2>
            <p class="text-sm text-cool-gray-500">{{ __('Manage and logout your active sessions on other browsers and devices.') }}</p>
        </x-slot>

        <div class="max-w-xl text-sm text-gray-600">
            <p>{{ __('If necessary, you may logout of all of your other browser sessions across all of your devices. If you feel your account has been compromised, you should also update your password.') }}</p>
        </div>

        @if ($this->sessions->count() >  0)
            <div class="mt-5 space-y-6">
                @foreach ($this->sessions as $session)
                    <div class="flex items-center">
                        <div>
                            @if ($session->agent->isDesktop())
                                <x-heroicon-o-desktop-computer class="h-8 w-8 text-gray-500" />
                            @else
                                <x-heroicon-o-device-mobile class="h-8 w-8 text-gray-500" />
                            @endif
                        </div>

                        <div class="ml-3">
                            <div class="text-sm text-gray-600">
                                {{ $session->agent->platform() }} - {{ $session->agent->browser() }}
                            </div>

                            <div>
                                <div class="text-xs text-gray-500">
                                    <span>{{ $session->ip_address }}</span>

                                    @if ($session->is_current_device)
                                        <span class="text-green-500 font-semibold">{{ __('This device') }}</span>
                                    @else
                                        {{ __('Last active') }} {{ $session->last_active }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="flex items-center mt-5 space-x-4">
            <x-button variant="blue" wire:click="confirmLogout">
                {{ __('Logout Other Browser Sessions') }}
            </x-button>

            <x-action-message on="logged_out">{{ __('Done.') }}</x-action-message>
        </div>
    </x-card>

    @include('livewire.profile.partials.logout-other-browser-sessions-dialog')
</div>
