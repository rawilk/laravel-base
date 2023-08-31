<?php

declare(strict_types=1);

use function Pest\Laravel\get;
use Rawilk\LaravelBase\LaravelBase;

test('views can be customized', function () {
    LaravelBase::loginView(fn () => view('login-custom')->layout('layout'));

    $response = get(route('login'));

    $response->assertOk();
    $response->assertSeeText('Foo');
});
