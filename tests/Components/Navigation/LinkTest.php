<?php

declare(strict_types=1);

beforeEach(function () {
    config(['app.url' => 'http://acme.test']);
});

it('can be rendered', function () {
    $this->blade('<x-link href="/">My link</x-link>')
        ->assertSeeInOrder([
            '<a',
            'href="/"',
            '>',
            'My link',
            '</a>',
        ], false);
});

it('gives a rel attribute to external links', function () {
    $this->blade('<x-link href="google.com">google</x-link>')
        ->assertSee('rel="nofollow noopener external', false);
});

test('referrer can be blocked on external links', function () {
    $this->blade('<x-link href="google.com" block-referrer>google</x-link>')
        ->assertSeeInOrder([
            'rel="',
            'noreferrer',
        ], false);
});

test('links can be made "dark"', function () {
    $this->blade('<x-link href="#" dark>dark link</x-link>')
        ->assertSeeInOrder([
            'class="',
            'app-link--dark',
        ], false);
});

test('flex classes can be removed', function () {
    $this->blade('<x-link href="#" :supports-icons="false">my link</x-link>')
        ->assertDontSeeText('flex')
        ->assertDontSeeText('items-center');
});
