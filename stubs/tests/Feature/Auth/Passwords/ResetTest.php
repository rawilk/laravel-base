<?php

declare(strict_types=1);

namespace Tests\Feature\Auth\Passwords;

use App\Models\User\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Rawilk\LaravelBase\Features;
use Rawilk\LaravelBase\Http\Livewire\Auth\Passwords\Email;
use Rawilk\LaravelBase\Http\Livewire\Auth\Passwords\Reset;
use Tests\TestCase;

final class ResetTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function password_reset_screen_can_be_rendered(): void
    {
        $this->skipIfFeatureIsDisabled();

        Notification::fake();

        $user = User::factory()->create();

        Livewire::test(Email::class)
            ->set('email', $user->email)
            ->call('sendPasswordResetLink');

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $this->get(route('password.reset', ['token' => $notification->token]))
                ->assertSuccessful()
                ->assertSeeLivewire('password.reset');

            return true;
        });
    }

    /** @test */
    public function user_can_reset_password_with_valid_token(): void
    {
        $this->skipIfFeatureIsDisabled();

        $user = User::factory()->create();

        $token = Str::random(16);

        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        Livewire::test(Reset::class, ['token' => $token])
            ->set('email', $user->email)
            ->set('password', 'new-password')
            ->call('resetPassword');

        $this->assertTrue(Auth::attempt([
            'email' => $user->email,
            'password' => 'new-password',
        ]));
    }

    /** @test */
    public function token_is_required(): void
    {
        $this->skipIfFeatureIsDisabled();

        Livewire::test(Reset::class, ['token' => ''])
            ->set('email', 'email@example.com')
            ->set('password', 'new-password')
            ->call('resetPassword')
            ->assertHasErrors(['token' => 'required']);
    }

    /** @test */
    public function email_is_required(): void
    {
        $this->skipIfFeatureIsDisabled();

        Livewire::test(Reset::class, ['token' => Str::random(16)])
            ->set('email', '')
            ->set('password', 'new-password')
            ->call('resetPassword')
            ->assertHasErrors(['email' => 'required']);
    }

    /** @test */
    public function email_must_be_valid_email(): void
    {
        $this->skipIfFeatureIsDisabled();

        Livewire::test(Reset::class, ['token' => Str::random(16)])
            ->set('email', 'foo')
            ->set('password', 'new-password')
            ->call('resetPassword')
            ->assertHasErrors(['email' => 'email']);
    }

    /** @test */
    public function password_is_required(): void
    {
        $this->skipIfFeatureIsDisabled();

        Livewire::test(Reset::class, ['token' => Str::random(16)])
            ->set('email', 'email@example.com')
            ->set('password', '')
            ->call('resetPassword')
            ->assertHasErrors(['password' => 'required']);
    }

    private function skipIfFeatureIsDisabled(): void
    {
        if (! Features::resetPasswords()) {
            $this->markTestSkipped('Reset password feature not enabled.');
        }
    }
}
