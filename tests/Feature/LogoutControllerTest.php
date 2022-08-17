<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests\Feature;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Mockery;
use function Pest\Laravel\post;

it('logs a user out', function () {
    Auth::guard()->setUser(
        Mockery::mock(Authenticatable::class)->shouldIgnoreMissing()
    );

    post(route('logout'))
        ->assertRedirect('/');

    expect(Auth::guard()->user())->toBeNull();
});
