<?php

declare(strict_types=1);

it('can be rendered', function () {
    $this->blade('<x-tr />')
        ->assertSee('<tr', false);
});

it('can have content and attributes', function () {
    $template = <<<'HTML'
        <x-tr row-index="2" wire-loads selected tab-index="-1">
            <td>My cell</td>
        </x-tr>
        HTML;

    $this->blade($template)
        ->assertSee('aria-rowindex="2"', false)
        ->assertSee('tabindex="-1"', false)
        ->assertSee('wire:loading.class.delay="opacity-50"', false)
        ->assertSee('class="bg-orange-100"', false)
        ->assertSee('<td>My cell</td>', false);
});

it('can have custom classes', function () {
    $this->blade('<x-tr class="my-extra-class" />')
        ->assertSeeInOrder([
            'class="',
            'my-extra-class',
            '"',
        ], false);
});
