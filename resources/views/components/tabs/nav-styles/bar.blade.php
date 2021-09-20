<nav class="tab-style-bar | relative z-0 rounded-lg shadow flex divide-x divide-gray-200"
     @include('laravel-base::components.tabs.partials.tab-nav-attrs')
>
    <template x-for="(tab, index) in tabs">
        <a class="text-blue-gray-900 group last:rounded-r-lg relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium inline-flex items-center justify-center text-center focus:outline-none"
           x-bind:class="{
               'focus:z-10 hover:bg-gray-50 hover:text-blue-gray-700': ! tab.disabled,
               'opacity-25 cursor-not-allowed': tab.disabled,
               'rounded-l-lg': index === 0,
           }"
           @include('laravel-base::components.tabs.partials.nav-link-attrs')
        >
            <span x-text="tab.name"></span>
            <span aria-hidden="true"
                  class="absolute inset-x-0 bottom-0 h-0.5"
                  x-bind:class="{
                     'bg-blue-500': activeTab === tab.id,
                     'bg-transparent': activeTab !== tab.id,
                  }"
            >
            </span>
        </a>
    </template>
</nav>
