<nav class="tab-style-pills | flex space-x-4"
     @include('laravel-base::components.tabs.partials.tab-nav-attrs')
>
    <template x-for="(tab, index) in tabs">
        <a class="px-3 py-2 font-medium text-sm rounded-md"
           x-bind:class="{
               'text-blue-gray-600 hover:text-blue-gray-800': activeTab !== tab.id && ! tab.disabled,
               'bg-blue-gray-200 text-blue-gray-800': activeTab === tab.id,
               'opacity-25 cursor-not-allowed': tab.disabled,
           }"
           @include('laravel-base::components.tabs.partials.nav-link-attrs')
           x-text="tab.name"
        >
        </a>
    </template>
</nav>
