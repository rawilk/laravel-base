<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests;

use Rawilk\LaravelBase\LaravelBase;

final class LaravelBaseTest extends TestCase
{
    /** @test */
    public function views_can_be_customized(): void
    {
        LaravelBase::loginView(fn () => view('login-custom')->layout('layout'));

        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSeeText('Foo');
    }
}
