<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests\Components\Layouts;

it('can be rendered', function () {
    $this->blade('<x-html />')
        ->assertSeeInOrder([
            '<!DOCTYPE html>',
            '<html',
            '<head',
            '</head>',
            '<body',
            '</body>',
            '</html>',
        ], false);
});

it('can have content', function () {
    $this->blade('<x-html><div>My content here</div></x-html>')
        ->assertSeeInOrder([
            '<body',
            '<div>My content here</div>',
            '</body>',
        ], false);
});

it('accepts a title', function () {
    $this->blade('<x-html title="My custom title"></x-html>')
        ->assertSee('<title>My custom title</title>', false);
});

test('body can have custom attributes', function () {
    $this->blade('<x-html class="my-class"></x-html>')
        ->assertSee('<body class="my-class"', false);
});

it('can have custom content at top of head', function () {
    $template = <<<'HTML'
    <x-html>
        <x-slot:headTop>
            <link rel="icon" href="favicon.ico" />
        </x-slot:headTop>
    </x-html>
    HTML;

    $this->blade($template)
        ->assertSeeInOrder([
            '<link rel="icon" href="favicon.ico" />',
            '<title',
        ], false);
});

it('can have slotted head content', function () {
    $template = <<<'HTML'
    <x-html>
        <x-slot:head>
            <link rel="icon" href="favicon.ico" />
        </x-slot:head>

        <div>My content</div>
    </x-html>
    HTML;

    $this->blade($template)
        ->assertSeeInOrder([
            '<link rel="icon" href="favicon.ico" />',
            '</head>',
            '<body',
            '<div>My content</div>',
        ], false);
});

it('can assign attributes to the html tag via slot', function () {
    $template = <<<'HTML'
    <x-html class="my-body-class">
        <x-slot:html class="my-html-class" data-foo="bar"></x-slot:html>

        <div>My content</div>
    </x-html>
    HTML;

    $this->blade($template)
        ->assertSeeInOrder([
            '<html',
            'class="my-html-class" data-foo="bar"',
            '>',
            '<body',
            'class="my-body-class"',
            '>',
            '<div>My content</div>',
        ], false);
});
