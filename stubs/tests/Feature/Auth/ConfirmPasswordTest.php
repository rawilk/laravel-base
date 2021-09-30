<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Rawilk\LaravelBase\Http\Livewire\Auth\ConfirmPassword;
use Tests\TestCase;

final class ConfirmPasswordTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/must-be-confirmed', fn () => 'You must be confirmed to see this page.')
            ->middleware(['web', 'password.confirm']);
    }

    /** @test */
    public function a_user_must_confirm_their_password_before_visiting_a_protected_page(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/must-be-confirmed')
            ->assertRedirect(route('password.confirm'));

        $this->followingRedirects()
            ->get('/must-be-confirmed')
            ->assertSeeLivewire('password.confirm');
    }

    /** @test */
    public function password_must_be_correct_to_confirm_it(): void
    {
        $user = User::factory()->create(['password' => 'secret']);
        $this->actingAs($user);

        Livewire::test(ConfirmPassword::class)
            ->set('password', 'incorrect-password')
            ->call('confirm')
            ->assertHasErrors('password');
    }

    /** @test */
    public function confirming_your_password_will_redirect_to_intended_page(): void
    {
        $this->actingAs(User::factory()->create(['password' => 'secret']));

        $this->withSession(['url.intended' => '/must-be-confirmed']);

        Livewire::test(ConfirmPassword::class)
            ->set('password', 'secret')
            ->call('confirm')
            ->assertRedirect('/must-be-confirmed');
    }
}
