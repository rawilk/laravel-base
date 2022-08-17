<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests\Components;

trait ComponentPrefixTestCase
{
    protected function getEnvironmentSetup($app)
    {
        parent::getEnvironmentSetup($app);

        $app['config']->set('laravel-base.component_prefix', 'tw');
    }
}
