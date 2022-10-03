<div x-data>
    <x-laravel-base::modal.slide-over-form wire:model.defer="open">
        <x-slot:title>{{ __('base::messages.modal.import.title') }}</x-slot:title>

        <div class="flex h-full flex-col">
            @include('laravel-base::livewire.imports.partials.file-uploader')
            @include('laravel-base::livewire.imports.partials.column-selection')
        </div>

        <x-slot:footerBefore>
            <livewire:csv-imports
                :import-class="$importClass"
                :model="$model"
            />
        </x-slot:footerBefore>

        <x-slot:footer>
            <div class="flex flex-shrink-0 justify-end">
                <x-laravel-base::button.button
                    variant="blue"
                    type="submit"
                    form="import{{ $this->id }}form"
                    :disabled="$fileRowCount === 0"
                    wire:target="import"
                >
                    {{ __('base::messages.modal.import.button') }}
                </x-laravel-base::button.button>
            </div>
        </x-slot:footer>
    </x-laravel-base::modal.slide-over-form>
</div>
