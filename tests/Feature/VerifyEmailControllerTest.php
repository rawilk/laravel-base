<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests\Feature;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\URL;
use Mockery;
use Rawilk\LaravelBase\Features;
use Rawilk\LaravelBase\Tests\TestCase;

final class VerifyEmailControllerTest extends TestCase
{
    public function getEnvironmentSetUp($app): void
    {
        $features = array_merge($app['config']->get('laravel-base.features', []), [
            Features::emailVerification(),
        ]);

        $app['config']->set('laravel-base.features', $features);

        parent::getEnvironmentSetUp($app);
    }

    /** @test */
    public function email_can_be_verified(): void
    {
        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => 1,
                'hash' => sha1('email@example.com'),
            ]
        );

        $user = Mockery::mock(Authenticatable::class);
        $user->shouldReceive('getKey')->andReturn(1);
        $user->shouldReceive('getAuthIdentifier')->andReturn(1);
        $user->shouldReceive('getEmailForVerification')->andReturn('email@example.com');
        $user->shouldReceive('hasVerifiedEmail')->andReturn(false);
        $user->shouldReceive('markEmailAsVerified')->once();

        $response = $this->actingAs($user)
            ->withSession(['url.intended' => 'http://foo.com/bar'])
            ->get($url);

        $response->assertRedirect('http://foo.com/bar');
    }

    /** @test */
    public function redirects_if_email_is_already_verified(): void
    {
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

        $this->actingAs($user)->get($url)->assertStatus(302);
    }

    /** @test */
    public function email_is_not_verified_if_id_does_not_match(): void
    {
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

        $this->actingAs($user)->get($url)->assertStatus(403);
    }

    /** @test */
    public function email_is_not_verified_if_email_does_not_match(): void
    {
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

        $this->actingAs($user)->get($url)->assertStatus(403);
    }
}
