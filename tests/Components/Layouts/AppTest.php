<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests\Components\Layouts;

use Rawilk\LaravelBase\Tests\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

final class AppTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function can_be_rendered(): void
    {
        $this->assertMatchesSnapshot(
            (string) $this->blade('<x-app :livewire="false" :laravel-form-components="false" />')
        );
    }

    /** @test */
    public function can_include_livewire_and_laravel_form_components_scripts_automatically(): void
    {
        $rendered = (string) $this->blade(
            '<x-app livewire laravel-form-components />'
        );

        $this->assertStringContainsString('livewire', $rendered);
        $this->assertStringContainsString('form-components', $rendered);
    }

    /** @test */
    public function custom_attributes_are_forwarded_to_the_body_tag(): void
    {
        $this->assertMatchesSnapshot(
            (string) $this->blade('<x-app :livewire="false" :laravel-form-components="false" class="my-body-class" id="my-body" />')
        );
    }

    /** @test */
    public function renders_title_correctly(): void
    {
        $template = <<<HTML
        <x-app :livewire="false" :laravel-form-components="false" title="My Custom Title">
        </x-app>
        HTML;

        $this->assertMatchesSnapshot((string) $this->blade($template));
    }

    /** @test */
    public function can_have_a_custom_title_separator(): void
    {
        $template = <<<HTML
        <x-app :livewire="false" :laravel-form-components="false" title="My Custom Title" title-separator="-">
        </x-app>
        HTML;

        $this->assertMatchesSnapshot((string) $this->blade($template));
    }

    /** @test */
    public function renders_content_in_default_slot(): void
    {
        $template = <<<HTML
        <x-app :livewire="false" :laravel-form-components="false">
            <div>My content</div>
        </x-app>
        HTML;

        $this->assertMatchesSnapshot((string) $this->blade($template));
    }

    /** @test */
    public function tags_can_be_added_to_head_via_slot(): void
    {
        $template = <<<HTML
        <x-app :livewire="false" :laravel-form-components="false">
            <x-slot name="headTop">
                <link rel="stylesheet" href="/css/top-styles.css">
            </x-slot>

            <x-slot name="head">
                <link rel="stylesheet" href="/css/app.css">
            </x-slot>

            <div>My content</div>
        </x-app>
        HTML;

        $this->assertMatchesSnapshot((string) $this->blade($template));
    }

    /** @test */
    public function tags_can_be_added_to_head_via_slot_or_stacks(): void
    {
        $template = <<<HTML
        <x-app :livewire="false" :laravel-form-components="false">
            <x-slot name="headTop">
                <link rel="stylesheet" href="/css/top-styles.css">
            </x-slot>

            <x-slot name="head">
                <link rel="stylesheet" href="/css/app.css">
            </x-slot>

            <div>My content</div>

            @push('head-top')
                <link rel="stylesheet" href="/css/other-top-styles.css">
            @endpush

            @push('head')
                <link rel="stylesheet" href="/css/other-styles.css">
            @endpush
        </x-app>
        HTML;

        $this->assertMatchesSnapshot((string) $this->blade($template));
    }

    /** @test */
    public function scripts_can_be_added_to_end_of_body_via_slot_or_stacks(): void
    {
        $template = <<<HTML
        <x-app :livewire="false" :laravel-form-components="false">
            <div>My content</div>

            <x-slot name="js">
                <script>alert('my script')</script>
            </x-slot>

            @push('js')
                <script>alert('my other script')</script>
            @endpush
        </x-app>
        HTML;

        $this->assertMatchesSnapshot((string) $this->blade($template));
    }
}
