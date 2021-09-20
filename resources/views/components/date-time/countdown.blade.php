<div x-data="countdown({{ $optionsToJson() }})"
     @if ($onFinish)
         x-on:timer-finished="() => { {{ $onFinish }} }"
     @endif
     {{ $attributes }}
>
    <div class="flex">
        @if ($slot->isEmpty())
            <span x-show="timer.days > 0" x-text="`${timer.days}:`"></span>
            <span x-show="timer.hours > 0" x-text="`${timer.hours}:`"></span>
            <span x-text="`${timer.minutes}:`"></span>
            <span x-text="timer.seconds"></span>
        @else
            {{ $slot }}
        @endif
    </div>
</div>
