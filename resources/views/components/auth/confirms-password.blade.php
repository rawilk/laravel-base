@php($confirmableId = md5($confirmableId ?? $attributes->wire('then')))

<span
    {{ $attributes->wire('then') }}
    x-data
    x-ref="span"
    x-on:click="$wire.startConfirmingPassword('{{ $confirmableId }}')"
    x-on:password-confirmed.window="setTimeout(() => $event.detail.id === '{{ $confirmableId }}' && $refs.span.dispatchEvent(new CustomEvent('then', { bubbles: false })), 250);"
    {{ $attributes->class('inline') }}
>
    {{ $slot }}
</span>

@once
    <x-laravel-base::modal.dialog-modal
        wire:model.defer="confirmingPassword"
        max-width="lg"
    >
        <x-slot name="title">{{ $title }}</x-slot>

        <x-slot name="content">
            <p>{{ $content }}</p>

            <div class="mt-4">
                <x-form-components::inputs.password
                    wire:model.defer="confirmablePassword"
                    wire:keydown.enter="confirmPassword"
                    name="confirmablePassword"
                    placeholder="{{ __('base::messages.confirms_password.password_placeholder') }}"
                    autocomplete="current-password"
                    focus
                />

                <x-form-components::form-error name="confirmablePassword" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-blade::button.button
                wire:click="confirmPassword"
                color="{{ $confirmButtonVariant }}"
            >
                {{ $button }}
            </x-blade::button.button>

            <x-blade::button.button
                wire:click="stopConfirmingPassword"
                wire:loading.attr="disabled"
                color="{{ $cancelButtonVariant }}"
            >
                {{ __('base::messages.confirm_modal_cancel') }}
            </x-blade::button.button>
        </x-slot>
    </x-laravel-base::modal.dialog-modal>
@endonce
