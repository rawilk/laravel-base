<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests\Components\Table;

use Rawilk\LaravelBase\Tests\TestCase;

final class TableTest extends TestCase
{
    /** @test */
    public function can_be_rendered(): void
    {
        $this->blade('<x-table />')
            ->assertSeeInOrder([
                '<table',
                '<tbody',
            ], false);
    }

    /** @test */
    public function can_have_thead_and_tbody_content(): void
    {
        $template = <<<'HTML'
        <x-table>
            <x-slot name="head">
                <tr>
                    <th>My heading</th>
                </tr>
            </x-slot>

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
    }

    /** @test */
    public function custom_attributes_can_be_applied(): void
    {
        $template = <<<'HTML'
        <x-table id="my-table" class="my-table-class">
            <x-slot name="head" id="my-th" class="th-class">
                <tr><th>My heading</th></tr>
            </x-slot>

            <x-slot name="tbody" id="my-tbody" class="tbody-class" x-ref="tbody"></x-slot>

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
    }
}
