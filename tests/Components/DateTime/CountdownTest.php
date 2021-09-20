<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests\Components\DateTime;

use Rawilk\LaravelBase\Tests\TestCase;

final class CountdownTest extends TestCase
{
    /** @test */
    public function can_be_rendered(): void
    {
        $expires = now()->addMinute();

        $template = <<<'HTML'
        <x-countdown :expires="$expires" />
        HTML;

        $this->blade($template, compact('expires'))
            ->assertSee('x-data="countdown(', false)
            ->assertSee('x-text="timer.seconds"', false)
            ->assertDontSee('x-on:timer-finished', false);
    }

    /** @test */
    public function custom_js_can_be_executed_when_the_timer_expires(): void
    {
        $expires = now()->addSeconds(30);

        $template = <<<'HTML'
        <x-countdown :expires="$expires">
            <x-slot name="onFinish">$wire.$refresh()</x-slot>
        </x-countdown>
        HTML;

        $this->blade($template, compact('expires'))
            ->assertSee('x-on:timer-finished="() => { $wire.$refresh() }"', false);
    }

    /** @test */
    public function custom_timer_text_can_be_used(): void
    {
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
    }
}
