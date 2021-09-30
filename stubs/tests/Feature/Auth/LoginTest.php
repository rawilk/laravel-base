<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Rawilk\LaravelBase\Http\Livewire\Auth\Login;
use Tests\TestCase;

final class LoginTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function can_see_login_screen(): void
    {
        $this->get(route('login'))
            ->assertSuccessful()
            ->assertSeeLivewire('login');
    }

    /** @test */
    public function is_redirected_if_already_logged_in(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('login'))
            ->assertRedirect(config('laravel-base.home'));
    }

    /** @test */
    public function users_can_login(): void
    {
        $user = User::factory()->create(['password' => 'secret']);

        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'secret')
            ->call('login');

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function is_redirected_to_home_page_after_login(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'password')
            ->call('login')
            ->assertRedirect(config('laravel-base.home'));
    }

    /** @test */
    public function email_is_required(): void
    {
        Livewire::test(Login::class)
            ->set('password', 'password')
            ->call('login')
            ->assertHasErrors(['email' => 'required']);
    }

    /** @test */
    public function password_is_required(): void
    {
        Livewire::test(Login::class)
            ->set('email', 'email@domain.com')
            ->call('login')
            ->assertHasErrors(['password' => 'required']);
    }

    /** @test */
    public function bad_login_attempt_shows_message(): void
    {
        $user = User::factory()->create(['password' => 'secret']);

        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'invalid-password')
            ->call('login')
            ->assertHasErrors('email');

        $this->assertGuest();
    }
}
