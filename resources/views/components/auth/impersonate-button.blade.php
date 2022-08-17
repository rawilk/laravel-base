@props([
    'userId' => '',
])

<span x-data="impersonate({{ \Illuminate\Support\Js::from([
    'userId' => $userId,
    'impersonateUrl' => route('admin.impersonate'),
]) }})"
x-on:click.prevent.stop="startImpersonating"
>
    {{ $slot }}
</span>
