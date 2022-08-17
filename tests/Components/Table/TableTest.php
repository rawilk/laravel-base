<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests\Components\Table;

it('can be rendered', function () {
    $this->blade('<x-table />')
        ->assertSeeInOrder([
            '<table',
            '<tbody',
        ], false);
});

it('can have thead and tbody content', function () {
    $template = <<<'HTML'
        <x-table>
            <x-slot:head>
                <tr>
                    <th>My heading</th>
                </tr>
            </x-slot:head>
            <tr>
                <td>My row</td>
            </tr>
        </x-table>
        HTML;

    $this->blade($template)
        ->assertSeeTextInOrder([
            'My heading',
            'My row',
        ])
        ->assertSeeInOrder([
            '<thead',
            '<tbody',
        ], false);
});

test('custom attributes can be applied', function () {
    $template = <<<'HTML'
        <x-table id="my-table" class="my-table-class">
            <x-slot:head id="my-th" class="th-class">
                <tr><th>My heading</th></tr>
            </x-slot:head>
            <x-slot:tbody id="my-tbody" class="tbody-class" x-ref="tbody"></x-slot:tbody>
            <tr><td>My row</td></tr>
        </x-table>
        HTML;

    $this->blade($template)
        ->assertSeeInOrder([
            'id="my-table"',
            'id="my-th"',
            'id="my-tbody"',
        ], false)
        ->assertSee('x-ref="tbody"', false)
        ->assertSee('tbody-class')
        ->assertSee('th-class')
        ->assertSeeText('My row');
});
