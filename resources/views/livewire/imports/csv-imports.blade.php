<div @class([
        'border-t border-gray-200' => $this->imports->isNotEmpty(),
    ])
    x-bind:class="{ 'pb-2': collapsed }"
    x-data="{ collapsed: false }"
    wire:poll.2s.visible
>
    <div>
        @if ($this->imports->isNotEmpty())
            <div class="flex items-center justify-between px-6 pt-2 group cursor-pointer" x-on:click="collapsed = ! collapsed; $refs.button.focus()">
                <div>
                    <span class="text-sm font-semibold text-slate-600">{{ __('base::messages.modal.imports.title') }}</span>
                </div>
                <div>
                    <button
                        type="button"
                        class="rounded-full p-2.5 bg-transparent group-hover:bg-gray-100 transition-colors focus:ring-2 focus:ring-blue-500"
                        x-ref="button"
                    >
                        <span class="sr-only" x-text="collapsed ? '{{ __('base::messages.modal.imports.expand_button') }}' : '{{ __('base::messages.modal.imports.collapse_button') }}'"></span>
                        <x-heroicon-s-chevron-down
                            class="h-4 w-4 text-slate-500 group-hover:text-slate-600 transition-colors"
                            x-bind:class="{ 'rotate-180': collapsed }"
                        />
                    </button>
                </div>
            </div>
        @endif
    </div>

    <div x-show="! collapsed"
         x-collapse
         class="max-h-[275px] overflow-y-auto"
    >
        @foreach ($this->imports as $import)
            <div class="p-6">
                <div>
                    <h2 class="font-medium">{{ __('base::messages.modal.imports.header', ['filename' => $import->file_name]) }}</h2>
                    <span class="text-gray-700 text-sm">
                    {{ __('base::messages.modal.imports.progress', ['processed' => $import->processed_rows, 'total' => $import->total_rows]) }}
                </span>
                    <div class="w-full bg-blue-100 rounded">
                        <div class="mt-2 w-full bg-blue-500 rounded h-2" style="width: {{ $import->percentageComplete() }}%;"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
