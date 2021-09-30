@extends('pages.profile.layout', [
    'title' => __('My profile'),
])

@section('slot')
    @if (\Rawilk\LaravelBase\Features::canUpdateProfileInformation())
        <livewire:profile.update-profile-information-form />
    @endif

    <livewire:profile.logout-other-browser-sessions-form />

    @if (\Rawilk\LaravelBase\Features::hasAccountDeletionFeatures())
        <livewire:profile.delete-user-form />
    @endif
@endsection
