<div class="grid grid-cols-6 pb-4">
    <div class="col-span-6 sm:col-span-4 space-y-4">
        <x-label>{{ __('Avatar') }}</x-label>

        {{-- current avatar --}}
        @if ($this->user->getKey() && (! $photo || $errors->has('photo')))
            <div id="current-photo">
                <img src="{{ $this->user->avatar_url }}" alt="{{ $this->user->name->full }}" class="rounded-full h-20 w-20 object-cover">
            </div>
        @endif

        {{-- New Avatar Photo Preview --}}
        @if ($photo && ! $errors->has('photo'))
            <div id="new-photo">
                <span class="block rounded-full h-20 w-20"
                      style="background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url('{{ $photo->temporaryUrl() }}');"
                >
                </span>
            </div>
        @endif

        <x-file-upload
            wire:model="photo"
            name="photo"
            label="{{ __('Select A New Photo') }}"
        />

        <div>
            @if ($photo)
                <x-button wire:click="cancelUpload" variant="red" id="cancel-upload-button">
                    {{ __('Cancel Upload') }}
                </x-button>
            @elseif ($this->user->avatar_path)
                <x-button wire:click="deleteProfilePhoto"
                          variant="red"
                          id="remove-photo-button"
                >
                    {{ __('Remove Photo') }}
                </x-button>
            @endif
        </div>
    </div>
</div>
