
<div @class(['p-4 sm:p-6' => ! $file])>
    @unless ($file)
    <div>
        {{-- file drop --}}
        <div x-data="{
                dropping: false,
                handleDrop(event) {
                    @this.upload('file', event.dataTransfer.files[0]);
                },
                handleClick(event) {
                    if (! this.$refs.label.contains(event.target)) {
                        this.$refs.input.click();
                    }
                },
             }"
             x-bind:class="{
                '{{ $errors->has('file') ? 'border-red-300' : 'border-gray-300' }}': ! dropping,
                '{{ $errors->has('file') ? 'border-red-400 bg-red-50' : 'border-gray-400 bg-gray-50' }}': dropping,
             }"
             x-on:dragover.prevent="dropping = true"
             x-on:dragleave.prevent="dropping = false"
             x-on:drop="dropping = false"
             x-on:drop.prevent="handleDrop($event)"
             x-on:click="handleClick($event)"
             @class([
                'max-w-lg mx-auto flex justify-center px-6 pt-4 pb-2 border-2 border-dashed rounded-md cursor-pointer transition-colors',
                'hover:bg-gray-50 hover:border-gray-400' => ! $errors->has('file'),
                'hover:bg-red-50 hover:border-red-400' => $errors->has('file'),
             ])
        >
            <div class="space-y-1 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <div class="flex text-sm text-slate-600">
                    <label for="csvFile" class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500" x-ref="label">
                        <span>{{ __('base::messages.modal.import.upload_file') }}</span>
                        <input id="csvFile" wire:model="file" name="file" type="file" class="sr-only" x-ref="input" accept="text/csv">
                    </label>
                    <p class="pl-1">{{ __('base::messages.modal.import.drag_drop') }}</p>
                </div>
                <p class="text-xs text-slate-500">
                    {{ __('base::messages.modal.import.upload_hint', ['max' => $this->maxFileSize->format()]) }}
                </p>
            </div>
        </div>
    </div>
    @endunless

    @error('file')
        <div class="max-w-lg mx-auto mt-2">
            <span class="mt-2 text-red-500 font-medium text-sm">{{ $message }}</span>
        </div>
    @enderror
</div>

@if ($file)
    <div class="flex items-center space-x-2">
        <p class="font-semibold">{{ __('base::messages.modal.import.importing') }}</p>

        <div>
            <x-laravel-base::elements.badge variant="blue">
                {{ $file->getClientOriginalName() }}
            </x-laravel-base::elements.badge>
        </div>

        <x-laravel-base::button.link
            class="text-xs"
            wire:click="cancelUpload"
            wire:target="cancelUpload"
        >
            {{ __('base::messages.modal.import.change_upload') }}
        </x-laravel-base::button.link>
    </div>
@endif
