@if ($fileHeaders)
    <x-form-components::form wire:submit.prevent="import" id="import{{ $this->id }}form">
        <div class="mt-8">
            <h2 class="font-medium">{{ __('base::messages.modal.import.map_columns') }}</h2>

            <div class="space-y-6 mt-5">
                @foreach ($columnsToMap as $column => $value)
                    <x-form-components::form-group name="columnsToMap.{{ $column }}" input-id="import{{ $this->id }}{{ $column }}" :optional="! $this->columnIsRequired($column)">
                        <x-slot:label>
                            {{ $columnLabels[$column] ?? $column }}
                        </x-slot:label>

                        <x-form-components::inputs.select
                            name="columnsToMap.{{ $column }}"
                            id="import{{ $this->id }}{{ $column }}"
                            wire:model.defer="columnsToMap.{{ $column }}"
                        >
                            <option value="">{{ __('base::messages.modal.import.ignore_column') }}</option>
                            @foreach ($fileHeaders as $fileHeader)
                                <option value="{{ $fileHeader }}">{{ $fileHeader }}</option>
                            @endforeach
                        </x-form-components::inputs.select>
                    </x-form-components::form-group>
                @endforeach
            </div>
        </div>
    </x-form-components::form>
@endif
