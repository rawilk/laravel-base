<div>
    <x-laravel-base::modal.import-modal
        wire:model="showImport"
        :upload="$upload"
        title="{{ __('laravel-base::roles.import.title') }}"
    >
        <x-laravel-base::modal.partials.import-mapped-fields
            :fields="$this->fieldsToMap()"
            :columns="$columns"
            key-prefix="roleImport"
        />
    </x-laravel-base::modal.import-modal>
</div>
