@extends('pages.profile.layout', [
    'title' => __('Authentication'),
])

@section('slot')
    @if (\Rawilk\LaravelBase\Features::enabled(\Rawilk\LaravelBase\Features::updatePasswords()))
        <livewire:profile.update-password-form />
    @endif

    @if (\Rawilk\LaravelBase\Features::canManageTwoFactorAuthentication())
        <livewire:profile.two-factor-authentication-form />
    @endif
@endsection
