<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests\Components\Table;

use Rawilk\LaravelBase\Tests\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

final class TrTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function can_be_rendered(): void
    {
        $this->assertMatchesSnapshot((string) $this->blade('<x-tr />'));
    }

    /** @test */
    public function can_have_content_and_attributes(): void
    {
        $template = <<<HTML
        <x-tr row-index="2" wire-loads selected tab-index="-1">
            <td>My cell</td>
        </x-tr>
        HTML;

        $this->assertMatchesSnapshot((string) $this->blade($template));
    }

    /** @test */
    public function can_have_custom_classes(): void
    {
        $this->assertMatchesSnapshot((string) $this->blade('<x-tr class="my-extra-class" />'));
    }
}
