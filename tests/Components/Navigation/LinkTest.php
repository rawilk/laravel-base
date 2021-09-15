<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests\Components\Navigation;

use Rawilk\LaravelBase\Tests\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

final class LinkTest extends TestCase
{
    use MatchesSnapshots;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.url' => 'http://acme.test']);
    }

    /** @test */
    public function can_be_rendered(): void
    {
        $this->assertMatchesSnapshot((string) $this->blade('<x-link href="/">My link</x-link>'));
    }

    /** @test */
    public function external_links_are_given_a_rel_attribute(): void
    {
        $this->assertMatchesSnapshot((string) $this->blade('<x-link href="google.com">google</x-link>'));
    }

    /** @test */
    public function referrer_can_be_blocked_on_external_links(): void
    {
        $this->assertMatchesSnapshot((string) $this->blade('<x-link href="http://google.com" block-referrer>google</x-link>'));
    }

    /** @test */
    public function links_can_be_made_dark(): void
    {
        $this->assertMatchesSnapshot((string) $this->blade('<x-link href="#" dark>dark link</x-link>'));
    }

    /** @test */
    public function flex_classes_can_be_removed(): void
    {
        $this->assertMatchesSnapshot((string) $this->blade('<x-link href="http://acme.test/foo" :supports-icons="false">my link</x-link>'));
    }
}
