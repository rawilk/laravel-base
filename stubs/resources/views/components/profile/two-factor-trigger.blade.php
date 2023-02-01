@props([
    'action' => '',
    'variant' => 'blue',
    'confirmEnabled' => true,
])

@if ($confirmEnabled)
    <x-confirms-password wire:then="{{ $action }}">
        <x-blade::button.button color="{{ $variant }}" wire:target="{{ $action }}">
            {{ $slot }}
        </x-blade::button.button>
    </x-confirms-password>
@else
    <x-blade::button.button color="{{ $variant }}" wire:click="{{ $action }}" {{ $attributes }}>
        {{ $slot }}
    </x-blade::button.button>
@endif

