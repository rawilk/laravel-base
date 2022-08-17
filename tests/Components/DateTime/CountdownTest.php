<?php

declare(strict_types=1);

it('can be rendered', function () {
    $expires = now()->addMinute();

    $template = <<<'HTML'
    <x-countdown :expires="$expires" />
    HTML;

    $this->blade($template, compact('expires'))
        ->assertSee('x-data="countdown(', false)
        ->assertSee('x-text="timer.seconds"', false)
        ->assertDontSee('x-on:timer-finished', false);
});

test('custom js can be executed when the timer expires', function () {
    $expires = now()->addSeconds(30);

    $template = <<<'HTML'
    <x-countdown :expires="$expires">
        <x-slot name="onFinish">$wire.$refresh()</x-slot>
    </x-countdown>
    HTML;

    $this->blade($template, compact('expires'))
        ->assertSee('x-on:timer-finished="() => { $wire.$refresh() }"', false);
});

test('a custom timer can be used', function () {
    $expires = now()->addSeconds(30);

    $template = <<<'HTML'
    <x-countdown :expires="$expires">
        <span class="block mb-1" x-text="timer.minutes"></span>
        <span class="block">minutes remaining</span>
    </x-countdown>
    HTML;

    $this->blade($template, compact('expires'))
        ->assertSee('timer.minutes')
        ->assertSeeText('minutes remaining')
        ->assertDontSee('timer.seconds')
        ->assertDontSee('timer.hours');
});
