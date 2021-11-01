<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests\Components\Table;

use Rawilk\LaravelBase\Tests\TestCase;

final class TrTest extends TestCase
{
    /** @test */
    public function can_be_rendered(): void
    {
        $this->blade('<x-tr />')
            ->assertSee('<tr', false);
    }

    /** @test */
    public function can_have_content_and_attributes(): void
    {
        $template = <<<HTML
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
    }

    /** @test */
    public function can_have_custom_classes(): void
    {
        $this->blade('<x-tr class="my-extra-class" />')
            ->assertSee('my-extra-class');
    }
}
