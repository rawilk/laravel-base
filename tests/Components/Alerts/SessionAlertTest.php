<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests\Components\Alerts;

use Rawilk\LaravelBase\Tests\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

final class SessionAlertTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function can_be_rendered(): void
    {
        session()->flash('alert', 'Form was successfully submitted.');

        $this->assertMatchesSnapshot((string) $this->blade('<x-session-alert />'));
    }

    /** @test */
    public function alert_key_can_be_specified(): void
    {
        session()->flash('error', 'Form contains some errors.');

        $this->assertMatchesSnapshot((string) $this->blade('<x-session-alert type="error" />'));
    }

    /** @test */
    public function it_can_be_slotted(): void
    {
        session()->flash('alert', 'Form was successfully submitted.');

        $template = <<<'HTML'
        <x-session-alert>
            <span>Hello world</span>
            {{ $component->message() }}
        </x-session-alert>
        HTML;

        $this->assertMatchesSnapshot((string) $this->blade($template));
    }

    /** @test */
    public function multiple_messages_can_be_used(): void
    {
        session()->flash('alert', [
            'Form was successfully submitted.',
            'We have sent you a confirmation email.',
        ]);

        $template = <<<'HTML'
        <x-session-alert>
            <span>Hello world</span>
            {{ implode(PHP_EOL, $component->messages()) }}
        </x-session-alert>
        HTML;

        $this->assertMatchesSnapshot((string) $this->blade($template));
    }
}
