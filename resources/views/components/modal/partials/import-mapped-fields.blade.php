@props([
    'fields' => [],
    'columns' => [],
    'keyPrefix' => 'import',
])

@foreach ($fields as $field => $fieldDefinition)
    <x-form-components::form-group
        wire:key="{{ $keyPrefix }}-{{ $field }}"
        name="fieldColumnMap.{{ $field }}"
        label="{{ $fieldDefinition['label'] ?? '' }}"
        :optional="! ($fieldDefinition['required'] ?? false)"
        inline
    >
        <x-form-components::inputs.select
            wire:model.defer="fieldColumnMap.{{ $field }}"
            name="fieldColumnMap.{{ $field }}"
            :required="$fieldDefinition['required'] ?? false"
        >
            <option value=" " @if ($fieldDefinition['required'] ?? false) disabled @endif>{{ __('Select Column...') }}</option>
            @foreach ($columns as $column)
                <option value="{{ $column }}">{{ $column }}</option>
            @endforeach
        </x-form-components::inputs.select>

        @if ($fieldDefinition['hint'] ?? null)
            <x-slot name="helpText">
                <span class="text-xs italic">{{ $fieldDefinition['hint'] }}</span>
            </x-slot>
        @endif
    </x-form-components::form-group>
@endforeach
