<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

final class LogoutTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_log_out(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(config('laravel-base.home'));

        $this->assertGuest();
    }

    /** @test */
    public function a_guest_can_not_log_out(): void
    {
        $this->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
