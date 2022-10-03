<div x-cloak x-data="scrollToTopButton" class="scroll-to-top-button | absolute top-0 right-0 h-screen w-screen pointer-events-none z-[100]">
    <button
        x-on:click="toTop"
        type="button"
        x-bind:class="{
            'opacity-0 translate-y-1': ! show,
            'opacity-100 translate-y-0': show,
        }"
        {{ $attributes->class('rounded-full bg-slate-500 hover:bg-slate-700 text-white p-3 fixed bottom-5 right-5 pointer-events-auto transition duration-100 ease-css') }}
    >
        <span class="sr-only">{{ __('Back to top') }}</span>
        <x-heroicon-s-arrow-up class="h-5 w-5" aria-hidden="true" />
    </button>
</div>
