<span x-data="impersonate({{ \Illuminate\Support\Js::from(['stopImpersonateUrl' => route('admin.impersonate.leave')]) }})"
x-on:click.prevent.stop="stopImpersonating"
>
    {{ $slot }}
</span>
