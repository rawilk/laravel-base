<div x-data="notification({{ $optionsToJson() }})"
     x-on:notify.window="add($event.detail)"
     aria-live="assertive"
     class="fixed inset-0 flex flex-col space-y-4 items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:justify-end z-[100]"
>
    <template x-for="notice in notices" :key="notice.id">
        <div
            x-show="visible.includes(notice)"
            x-transition:enter="transition ease-in duration-200"
            x-transition:enter-start="transform opacity-0 translate-y-2"
            x-transition:enter-end="transform opacity-100"
            x-transition:leave="transition ease-out duration-500"
            x-transition:leave-start="transform translate-x-0 opacity-100"
            x-transition:leave-end="transform translate-x-full opacity-0"
            class="max-w-sm w-full bg-white dark:bg-gray-700 shadow-lg rounded-lg pointer-events-auto z-100"
        >
            <div class="rounded-lg shadow-xs overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <x-heroicon-o-check-circle class="h-6 w-6 text-green-400 dark:text-green-300" x-show="! notice.type || notice.type === 'success'" />

                            <x-heroicon-o-x-circle class="h-6 w-6 text-red-400 dark:text-red-300" x-show="notice.type === 'error'" />
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0 5">
                            <p x-html="notice.message" class="text-sm leading-5 font-medium text-slate-900 dark:text-slate-200"></p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button x-on:click="remove(notice.id);" class="inline-flex text-slate-400 dark:text-slate-300 focus:text-slate-500 dark:focus:text-slate-400 transition-colors z-top">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
