<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests\Components\Table;

use Rawilk\LaravelBase\Tests\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

final class TableTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function can_be_rendered(): void
    {
        $this->assertMatchesSnapshot((string) $this->blade('<x-table />'));
    }

    /** @test */
    public function can_have_thead_and_tbody_content(): void
    {
        $template = <<<HTML
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

        $this->assertMatchesSnapshot((string) $this->blade($template));
    }

    /** @test */
    public function custom_attributes_can_be_applied(): void
    {
        $template = <<<HTML
        <x-table id="my-table" class="my-table-class">
            <x-slot name="head" id="my-th" class="th-class">
                <tr><th>My heading</th></tr>
            </x-slot>

            <x-slot name="tbody" id="my-tbody" class="tbody-class" x-ref="tbody"></x-slot>

            <tr><td>My row</td></tr>
        </x-table>
        HTML;

        $this->assertMatchesSnapshot((string) $this->blade($template));
    }
}
