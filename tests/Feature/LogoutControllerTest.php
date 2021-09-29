<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests\Feature;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Rawilk\LaravelBase\Tests\TestCase;

final class LogoutControllerTest extends TestCase
{
    /** @test */
    public function user_can_logout(): void
    {
        Auth::guard()->setUser(
            Mockery::mock(Authenticatable::class)->shouldIgnoreMissing()
        );

        $this->post(route('logout'))
            ->assertRedirect('/');

        $this->assertNull(Auth::guard()->getUser());
    }
}
