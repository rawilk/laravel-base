<div class="tab-nav--mobile | sm:hidden mb-4">
    <label class="sr-only" for="tabs-{{ $id }}-select">{{ __('laravel-base::messages.tabs.select_tab') }}</label>
    <x-form-components::inputs.select
        aria-label="{{ __('laravel-base::messages.tabs.selected_tab_label') }}"
        role="tablist"
        id="tabs-{{ $id }}-select"
        x-on:input="selectTab($el.value)"
        x-model="activeTab"
    >
        <template x-for="tab in tabs">
            <option
                x-bind:value="tab.id"
                x-text="tab.name"
                x-bind:disabled="tab.disabled"
            >
            </option>
        </template>
    </x-form-components::inputs.select>
</div>
