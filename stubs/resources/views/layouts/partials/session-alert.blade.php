{{-- View Composer: SessionAlertViewComposer --}}
@foreach ($sessionAlertTypes as $type)
    <x-session-alert :type="$type">
        @php($message = $component->message())

        <x-alert :type="$type" :dismiss="$canDismissAlert ?? false" :role="null">
            <p>{!! $message !!}</p>
        </x-alert>
    </x-session-alert>
@endforeach
