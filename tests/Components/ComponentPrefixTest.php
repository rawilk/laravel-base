<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests\Components;

uses(ComponentPrefixTestCase::class);

test('a custom prefix can be used', function () {
    $this->blade('<x-tw-html title="Hello World"></x-tw-html>')
        ->assertSee('<!DOCTYPE html>', false)
        ->assertSee('<html', false)
        ->assertSee('<title>Hello World</title>', false);
});
