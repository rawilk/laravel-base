<?php

declare(strict_types=1);

it('can be rendered', function () {
    $this->blade('<x-app />')
        ->assertSeeInOrder([
            '<!DOCTYPE html>',
            '<title>Laravel</title>',
            '<body',
        ], false);
});

it('can include livewire and laravel form components scripts automatically', function () {
    $this->blade('<x-app />')
        ->assertSee('[wire\:loading]', false) // livewire styles
        ->assertSee('livewire.js')
        ->assertSee('<script src="/form-components', false);
});

it('forwards custom attributes to the body tag', function () {
    $this->blade('<x-app class="my-body-class" id="my-body" />')
        ->assertSeeInOrder([
            '<body',
            'class="my-body-class"',
            'id="my-body"',
            '>',
        ], false);
});

it('renders the title correctly', function () {
    config(['app.name' => 'Acme']);

    $this->blade('<x-app title="My custom title" />')
        ->assertSee('<title>My custom title | Acme</title>', false);
});

it('can have a custom title separator', function () {
    config(['app.name' => 'Acme']);

    $this->blade('<x-app title="My custom title" title-separator="-" />')
        ->assertSee('<title>My custom title - Acme</title>', false);
});

it('renders content in the body', function () {
    $template = <<<'HTML'
    <x-app>
        <div>My content</div>
    </x-app>
    HTML;

    $this->blade($template)
        ->assertSeeInOrder([
            '<body',
            '<div>My content</div>',
        ], false);
});

test('tags can be added to the head via slot', function () {
    $template = <<<'HTML'
    <x-app>
        <x-slot:head-top>
            <link rel="stylesheet" href="/css/top-styles.css" />
        </x-slot:head-top>

        <x-slot:head>
            <link rel="stylesheet" href="/css/app.css" />
        </x-slot:head>

        <div>My content</div>
    </x-app>
    HTML;

    $this->blade($template)
        ->assertSeeInOrder([
            '<head',
            '<link rel="stylesheet" href="/css/top-styles.css" />',
            '<title>',
            '<link rel="stylesheet" href="/css/app.css" />',
            '<body',
            '<div>My content</div>',
        ], false);
});

test('tags can be added via slot and stacks at same time', function () {
    $template = <<<'HTML'
    <x-app>
        <x-slot:head-top>
            <link rel="stylesheet" href="/css/top-styles.css" />
        </x-slot:head-top>

        <x-slot:head>
            <link rel="stylesheet" href="/css/app.css" />
        </x-slot:head>

        <div>My content</div>

        @push('head-top')
            <link rel="stylesheet" href="/css/other-top-styles.css" />
        @endpush

        @push('head')
            <link rel="stylesheet" href="/css/other-styles.css" />
        @endpush
    </x-app>
    HTML;

    $this->blade($template)
        ->assertSeeInOrder([
            '<head',
            '<link rel="stylesheet" href="/css/top-styles.css" />',
            '<link rel="stylesheet" href="/css/other-top-styles.css" />',
            '<title>',
            '<link rel="stylesheet" href="/css/app.css" />',
            '<link rel="stylesheet" href="/css/other-styles.css" />',
            '<body',
            '<div>My content</div>',
        ], false);
});

test('scripts can be added via slot or stacks', function () {
    $template = <<<'HTML'
    <x-app>
        <div>My content</div>

        <x-slot:js>
            <script>alert('my script')</script>
        </x-slot:js>

        @push('js')
            <script>alert('my other script')</script>
        @endpush
    </x-app>
    HTML;

    $this->blade($template)
        ->assertSeeInOrder([
            '<body',
            '<div>My content</div>',
            "<script>alert('my script')</script>",
            "<script>alert('my other script')</script>",
        ], false);
});
