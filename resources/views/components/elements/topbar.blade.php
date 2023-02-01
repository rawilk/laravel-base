@php($filterId = $componentSlot($filters)->attributes->get('id', 'advanced-filters'))

<div>
    @if ($filters && $filters->isNotEmpty())
        {{-- filtering --}}
        <x-laravel-base::modal.advanced-filters
            :id="$filterId"
            :apply-click="$filters->attributes->get('apply-click', 'applyFilters')"
            :reset-click="$filters->attributes->get('reset-click', 'resetFilters')"
            :close-on-apply="$filters->attributes->get('close-on-apply', true)"
            :close-on-reset="$filters->attributes->get('close-on-reset', true)"
        >
            {{ $filters }}
        </x-laravel-base::modal.advanced-filters>
    @endif

    <div class="bg-white border shadow-sm lg:flex lg:divide-x lg:divide-x-slate-200 divide-y divide-y-slate-200 lg:divide-y-0">
        {{-- search --}}
        <div class="lg:flex-1 lg:min-w-[50%] xl:min-w-[33.333%] px-2 py-2 flex items-center">
            @if ($searchModel)
                <div class="w-full flex items-center">
                    <x-form-components::inputs.input
                        wire:model.debounce.500ms="{{ $searchModel }}"
                        type="search"
                        placeholder="{{ $searchPlaceholder }}"
                        container-class="flex-1"
                    >
                        <x-slot name="leadingIcon">
                            <x-css-search />
                        </x-slot>
                    </x-form-components::inputs.input>

                    @if ($filters && $filterId)
                        <div class="flex-shrink-0 pl-1.5">
                            <x-tooltip title="{!! __('base::messages.filters.trigger_button_tooltip') !!}">
                                <button
                                    x-on:click="$dispatch('show-filters', '{{ $filterId }}')"
                                    x-on:keydown.ctrl.f.window="$dispatch('show-filters', '{{ $filterId }}')"
                                    type="button"
                                    class="p-2 rounded-full hover:bg-slate-200 group focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 focus:ring-opacity-50 focus:outline-none transition-colors"
                                >
                                    <x-heroicon-s-funnel class="w-4 h-4 text-slate-500 group-hover:text-slate-600 transition-colors" />
                                </button>
                            </x-tooltip>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div x-data="{ show: false }">
            <div class="px-2 pb-1 lg:hidden">
                <x-blade::button.link
                    x-on:click="show = ! show"
                    class="text-xs"
                >
                    <span x-text="show ? '{{ __('base::messages.labels.topbar.hide_menu') }}' : '{{ __('base::messages.labels.topbar.show_menu') }}'"></span>
                </x-blade::button.link>
            </div>

            <div class="flex flex-wrap items-center px-2"
                 x-bind:class="{ 'hidden lg:flex': ! show }"
            >
                {{-- per page --}}
                @if ($showPerPage)
                    <div class="topbar-section w-full lg:w-auto">
                        <div class="grid grid-cols-3 gap-4 items-center">
                            <x-form-components::label for="{{ $perPageModel }}">
                                {{ __('base::messages.labels.form.per_page') }}
                            </x-form-components::label>
                            <div class="col-span-2">
                                <x-form-components::inputs.select
                                    wire:model="{{ $perPageModel }}"
                                    name="{{ $perPageModel }}"
                                >
                                    @foreach ($perPageOptions as $perPageOption)
                                        <option value="{{ $perPageOption }}">{{ $perPageOption }}</option>
                                    @endforeach
                                </x-form-components::inputs.select>
                            </div>
                        </div>
                    </div>
                @endif

                {{ $slot }}

                {{-- column select --}}
                @if ($showColumns)
                    <div class="topbar-section">
                        <x-column-select :columns="$hideableColumns" :hidden="$hiddenColumns" />
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
