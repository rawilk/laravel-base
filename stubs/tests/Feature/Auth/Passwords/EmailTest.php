<?php

declare(strict_types=1);

namespace Tests\Feature\Auth\Passwords;

use App\Models\User\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Rawilk\LaravelBase\Features;
use Rawilk\LaravelBase\Http\Livewire\Auth\Passwords\Email;
use Tests\TestCase;

final class EmailTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function can_see_password_request_page(): void
    {
        $this->skipIfFeatureIsDisabled();

        $this->get(route('password.request'))
            ->assertSuccessful()
            ->assertSeeLivewire('password.email');
    }

    /** @test */
    public function authenticated_users_can_see_the_password_request_page(): void
    {
        $this->skipIfFeatureIsDisabled();

        $this->actingAs(User::factory()->create())
            ->get(route('password.request'))
            ->assertSuccessful()
            ->assertSeeLivewire('password.email');
    }

    /** @test */
    public function email_is_required(): void
    {
        $this->skipIfFeatureIsDisabled();

        Livewire::test(Email::class)
            ->call('sendPasswordResetLink')
            ->assertHasErrors(['email' => 'required']);
    }

    /** @test */
    public function email_must_be_a_valid_email(): void
    {
        $this->skipIfFeatureIsDisabled();

        Livewire::test(Email::class)
            ->set('email', 'foo')
            ->call('sendPasswordResetLink')
            ->assertHasErrors(['email' => 'email']);
    }

    /** @test */
    public function an_email_is_sent_for_existing_users(): void
    {
        $this->skipIfFeatureIsDisabled();

        Notification::fake();

        $user = User::factory()->create();

        Livewire::test(Email::class)
            ->set('email', $user->email)
            ->call('sendPasswordResetLink')
            ->assertSet('emailSent', true);

        $this->assertDatabaseHas('password_resets', [
            'email' => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    private function skipIfFeatureIsDisabled(): void
    {
        if (! Features::resetPasswords()) {
            $this->markTestSkipped('Reset password feature not enabled.');
        }
    }
}
