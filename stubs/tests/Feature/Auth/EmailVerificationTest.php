<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Rawilk\LaravelBase\Features;
use Rawilk\LaravelBase\Http\Livewire\Auth\Verify;
use Tests\TestCase;

final class EmailVerificationTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function email_verification_notice_can_be_rendered(): void
    {
        $this->skipTestIfFeatureIsDisabled();

        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->get(route('verification.notice'))
            ->assertSuccessful()
            ->assertSeeLivewire('verify-email');
    }

    /** @test */
    public function can_resend_verification_email(): void
    {
        $this->skipTestIfFeatureIsDisabled();

        $user = User::factory()->unverified()->create();

        Notification::fake();

        Livewire::actingAs($user)
            ->test(Verify::class)
            ->call('resend');

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /** @test */
    public function email_can_be_verified(): void
    {
        $this->skipTestIfFeatureIsDisabled();

        Event::fake();

        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(config('laravel-base.home') . '?verified=1');
    }

    /** @test */
    public function email_can_not_be_verified_with_invalid_hash(): void
    {
        $this->skipTestIfFeatureIsDisabled();

        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    private function skipTestIfFeatureIsDisabled(): void
    {
        if (! Features::enabled(Features::emailVerification())) {
            $this->markTestSkipped('Email verification is not enabled.');
        }
    }
}
