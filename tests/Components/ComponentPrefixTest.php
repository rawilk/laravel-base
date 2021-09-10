<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests\Components;

use Rawilk\LaravelBase\Tests\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

final class ComponentPrefixTest extends TestCase
{
    use MatchesSnapshots;

    public function getEnvironmentSetup($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('laravel-base.component_prefix', 'tw');
    }

    /** @test */
    public function a_custom_prefix_can_be_used(): void
    {
        $this->assertMatchesSnapshot(
            (string) $this->blade('<x-tw-html title="HTML with custom prefix" />')
        );
    }
}
