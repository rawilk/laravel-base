@if (isImpersonating())
    <div class="relative bg-slate-500">
        <div class="max-w-screen-xl mx-auto py-3 px-3 sm:px-6 lg:px-8">
            <div class="pr-16 sm:text-center sm:px-16">
                <p class="font-medium text-white">
                    <span>
                        {{ __('base::users.impersonate.notice', ['name' => auth()->user()->name->full, 'impersonator' => app(\Rawilk\LaravelBase\Contracts\Models\ImpersonatesUsers::class)->impersonatorName(request())]) }}
                    </span>

                    <span class="block sm:ml-2 sm:inline-block">
                        <x-laravel-base::auth.stop-impersonation-button>
                            <x-blade::button.link class="text-white hover:text-gray-300">
                                {{ __('base::users.impersonate.leave') }} &rarr;
                            </x-blade::button.link>
                        </x-laravel-base::auth.stop-impersonation-button>
                    </span>
                </p>
            </div>
        </div>
    </div>
@endif
