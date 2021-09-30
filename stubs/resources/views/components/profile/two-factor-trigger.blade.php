@props([
    'action' => '',
    'variant' => 'blue',
    'confirmEnabled' => true,
])

@if ($confirmEnabled)
    <x-confirms-password wire:then="{{ $action }}">
        <x-button variant="{{ $variant }}" wire:target="{{ $action }}">
            {{ $slot }}
        </x-button>
    </x-confirms-password>
@else
    <x-button variant="{{ $variant }}" wire:click="{{ $action }}" wire:target="{{ $action }}" {{ $attributes }}>
        {{ $slot }}
    </x-button>
@endif

