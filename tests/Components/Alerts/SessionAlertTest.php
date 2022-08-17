<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests\Components\Alerts;

it('can be rendered', function () {
    session()->flash('alert', 'Form was successfully submitted.');

    $this->blade('<x-session-alert />')
        ->assertSeeText('Form was successfully submitted.')
        ->assertSee('role="alert"', false);
});

test('alert key can be specified', function () {
    session()->flash('error', 'Form contains some errors.');

    $this->blade('<x-session-alert type="error" />')
        ->assertSee('Form contains some errors.');
});

it('can be slotted', function () {
    session()->flash('alert', 'Form was successfully submitted.');

    $template = <<<'HTML'
    <x-session-alert>
        <span>Hello world</span>
        {{ $component->message() }}
    </x-session-alert>
    HTML;

    $this->blade($template)
        ->assertSeeInOrder([
            'role="alert"',
            '<span>Hello world</span>',
            'Form was successfully submitted.',
        ], false);
});

it('can render multiple messages', function () {
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

    $this->blade($template)
        ->assertSeeInOrder([
            '<span>Hello world</span>',
            'Form was successfully submitted.',
            'We have sent you a confirmation email.',
        ], false);
});
