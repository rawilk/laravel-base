<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests\Feature;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Mockery;
use function Pest\Laravel\actingAs;
use Rawilk\LaravelBase\Features;
use Rawilk\LaravelBase\Http\Controllers\Auth\VerifyEmailController;
use Rawilk\LaravelBase\Http\Livewire\Auth\Verify;

beforeEach(function () {
    $features = array_merge(config('laravel-base.features', []), [
        Features::emailVerification(),
    ]);

    config()->set('laravel-base.features', $features);

    $verificationLimiter = config('laravel-base.limiters.verification', '6,1');

    Route::get('/email/verify', Verify::class)
        ->middleware(['auth:' . config('laravel-base.guard')])
        ->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['auth:' . config('laravel-base.guard'), 'signed', 'throttle:' . $verificationLimiter])
        ->name('verification.verify');
});

test('email can be verified', function () {
    $url = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => 1,
            'hash' => sha1('email@example.com'),
        ],
    );

    $user = Mockery::mock(Authenticatable::class);
    $user->shouldReceive('getKey')->andReturn(1);
    $user->shouldReceive('getAuthIdentifier')->andReturn(1);
    $user->shouldReceive('getEmailForVerification')->andReturn('email@example.com');
    $user->shouldReceive('hasVerifiedEmail')->andReturn(false);
    $user->shouldReceive('markEmailAsVerified')->once();

    $response = actingAs($user)
        ->withSession(['url.intended' => 'http://foo.com/bar'])
        ->get($url);

    $response->assertRedirect('http://foo.com/bar');
});

it('redirects if email is already verified', function () {
    $url = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => 1,
            'hash' => sha1('email@example.com'),
        ],
    );

    $user = Mockery::mock(Authenticatable::class);
    $user->shouldReceive('getKey')->andReturn(1);
    $user->shouldReceive('getAuthIdentifier')->andReturn(1);
    $user->shouldReceive('getEmailForVerification')->andReturn('email@example.com');
    $user->shouldReceive('hasVerifiedEmail')->andReturn(true);
    $user->shouldReceive('markEmailAsVerified')->never();

    actingAs($user)
        ->get($url)
        ->assertStatus(302);
});

it('does not verify email if id does not match', function () {
    $url = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => 2,
            'hash' => sha1('email@example.com'),
        ],
    );

    $user = Mockery::mock(Authenticatable::class);
    $user->shouldReceive('getKey')->andReturn(1);
    $user->shouldReceive('getAuthIdentifier')->andReturn(1);
    $user->shouldReceive('getEmailForVerification')->andReturn('email@example.com');

    actingAs($user)
        ->get($url)
        ->assertStatus(403);
});

it('does not verify email if the email does not match', function () {
    $url = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => 1,
            'hash' => sha1('other@example.com'),
        ],
    );

    $user = Mockery::mock(Authenticatable::class);
    $user->shouldReceive('getKey')->andReturn(1);
    $user->shouldReceive('getAuthIdentifier')->andReturn(1);
    $user->shouldReceive('getEmailForVerification')->andReturn('email@example.com');

    actingAs($user)
        ->get($url)
        ->assertStatus(403);
});
