<?php

declare(strict_types=1);

use Rawilk\LaravelBase\LaravelBase;

use function Pest\Laravel\get;

test('views can be customized', function () {
    LaravelBase::loginView(fn () => view('login-custom')->layout('layout'));

    $response = get(route('login'));

    $response->assertOk();
    $response->assertSeeText('Foo');
});
