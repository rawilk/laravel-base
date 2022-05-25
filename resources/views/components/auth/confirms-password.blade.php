@php($confirmableId = md5($attributes->wire('then')))

<span
    {{ $attributes->wire('then') }}
    x-data
    x-ref="span"
    x-on:click="$wire.startConfirmingPassword('{{ $confirmableId }}')"
    x-on:password-confirmed.window="setTimeout(() => $event.detail.id === '{{ $confirmableId }}' && $refs.span.dispatchEvent(new CustomEvent('then', { bubbles: false })), 250);"
    class="inline"
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
                placeholder="{{ __('laravel-base::messages.confirms_password.password_placeholder') }}"
                autocomplete="current-password"
                focus
            />

            <x-form-components::form-error name="confirmablePassword" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-laravel-base::button.button
            wire:click="confirmPassword"
            wire:target="confirmPassword"
            variant="{{ $confirmButtonVariant }}"
        >
            {{ $button }}
        </x-laravel-base::button.button>

        <x-laravel-base::button.button
            wire:click="stopConfirmingPassword"
            wire:loading.attr="disabled"
            variant="{{ $cancelButtonVariant }}"
        >
            {{ __('laravel-base::messages.confirm_modal_cancel') }}
        </x-laravel-base::button.button>
    </x-slot>
</x-laravel-base::modal.dialog-modal>
@endonce
