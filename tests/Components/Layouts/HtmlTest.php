<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests\Components\Layouts;

use Rawilk\LaravelBase\Tests\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

final class HtmlTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function can_be_rendered(): void
    {
        $this->assertMatchesSnapshot((string) $this->blade('<x-html />'));
    }

    /** @test */
    public function can_have_content(): void
    {
        $this->assertMatchesSnapshot(
            (string) $this->blade('<x-html><div>My content here</div></x-html>')
        );
    }

    /** @test */
    public function body_can_have_custom_attributes(): void
    {
        $this->assertMatchesSnapshot((string) $this->blade('<x-html class="my-class" />'));
    }

    /** @test */
    public function title_can_be_set(): void
    {
        $this->assertMatchesSnapshot((string) $this->blade('<x-html title="My custom title" />'));
    }

    /** @test */
    public function can_have_custom_content_at_top_of_head(): void
    {
        $template = <<<'HTML'
        <x-html>
            <x-slot name="headTop">
                <link rel="icon" href="favicon.ico" />
            </x-slot>

            <div>My content</div>
        </x-html>
        HTML;

        $this->assertMatchesSnapshot((string) $this->blade($template));
    }

    /** @test */
    public function can_have_slotted_head_content(): void
    {
        $template = <<<'HTML'
        <x-html>
            <x-slot name="head">
                <link rel="icon" href="favicon.ico" />
            </x-slot>

            <div>My content</div>
        </x-html>
        HTML;

        $this->assertMatchesSnapshot((string) $this->blade($template));
    }

    /** @test */
    public function can_assign_attributes_to_html_tag_via_slot(): void
    {
        $template = <<<'HTML'
        <x-html class="my-body-class">
            <x-slot name="html" class="my-html-class" data-foo="bar"></x-slot>

            <x-slot name="headTop">
                <link rel="icon" href="favicon.ico" />
            </x-slot>

            <div>My content</div>
        </x-html>
        HTML;

        $this->assertMatchesSnapshot((string) $this->blade($template));
    }
}
