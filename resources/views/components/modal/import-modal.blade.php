@includeIf($assetsPartial)

<x-dialog-modal :show-icon="false" :id="$id()" max-width="2xl" class="w-full" :title="$title" {{ $attributes }}>
    <x-slot name="content">
        @unless ($upload)
            <div class="py-12 flex flex-col items-center justify-between">
                <div class="filepond--centered space-y-4">
                    <x-css-software-upload class="text-gray-400 h-8 w-8 mx-auto" />

                    <x-form-components::files.file-pond
                        wire:model="upload"
                        description="{{ __('base::messages.modal.import.upload_hint') }}"
                    />
                </div>

                <x-form-components::form-error name="upload" />
            </div>
        @else
            <div class="flex items-center space-x-2 mb-6">
                <p class="font-semibold">{{ __('base::messages.modal.import.importing') }}:</p>

                <div>
                    <x-badge variant="blue">{{ $upload->getClientOriginalName() }}</x-badge>
                </div>

                <x-blade::button.link
                    class="text-xs"
                    x-on:click="$wire.removeUpload('upload', '{{ $upload->getFilename() }}')"
                    wire:target="removeUpload"
                >
                    {{ __('base::messages.modal.import.change_upload') }}
                </x-blade::button.link>
            </div>

            <x-form-components::form
                wire:submit.prevent="import"
                class="mt-5 w-full"
                id="{{ $id }}-import-form"
            >
                <div>
                    {{ $slot }}
                </div>
            </x-form-components::form>
        @endunless
    </x-slot>

    <x-slot name="footer">
        @if ($upload)
            <x-blade::button.button
                wire:target="import"
                type="submit"
                form="{{ $id }}-import-form"
                color="blue"
            >
                {{ $button }}
            </x-blade::button.button>
        @endif

        <x-blade::button.button
            x-on:click="$wire.set('{{ $showModel }}', false)"
            wire:loading.attr="disabled"
            color="white"
        >
            {{ __('base::messages.confirm_modal_cancel') }}
        </x-blade::button.button>
    </x-slot>
</x-dialog-modal>
