<div class="tab-style-underline | border-b border-slate-200">
    <nav @class([
            '-mb-px',
            'space-x-8 flex' => ! $fullWidthTabs,
            'grid sm:grid-flow-col sm:auto-cols-fr' => $fullWidthTabs,
        ])
        @include('laravel-base::components.tabs.partials.tab-nav-attrs')
    >
        <template x-for="(tab, index) in tabs">
            <a @class([
                   'py-4 px-1 border-b-2 font-medium text-sm',
                   'whitespace-nowrap' => ! $fullWidthTabs,
                   'text-center' => $fullWidthTabs,
               ])
               x-bind:class="{
                   'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== tab.id && ! tab.disabled,
                   'border-blue-500 text-blue-600': activeTab === tab.id,
                   'border-transparent opacity-25 cursor-not-allowed': tab.disabled,
                }"
                @include('laravel-base::components.tabs.partials.nav-link-attrs')
                x-text="tab.name"
            >
            </a>
        </template>
    </nav>
</div>
