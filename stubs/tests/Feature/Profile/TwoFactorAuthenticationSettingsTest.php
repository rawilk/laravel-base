<?php

declare(strict_types=1);

namespace Tests\Feature\Profile;

use App\Models\User\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Rawilk\LaravelBase\Http\Livewire\Profile\TwoFactorAuthenticationForm;
use Tests\TestCase;

final class TwoFactorAuthenticationSettingsTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function two_factor_authentication_can_be_enabled(): void
    {
        $this->actingAs($user = User::factory()->create());

        $this->withSession(['auth.password_confirmed_at' => time()]);

        Livewire::test(TwoFactorAuthenticationForm::class)
            ->call('enableTwoFactorAuthentication');

        $user = $user->fresh();

        $this->assertNotNull($user->two_factor_secret);
        $this->assertCount(8, $user->recoveryCodes());
    }

    /** @test */
    public function recovery_codes_can_be_regenerated(): void
    {
        $this->actingAs($user = User::factory()->create());

        $this->withSession(['auth.password_confirmed_at' => time()]);

        $component = Livewire::test(TwoFactorAuthenticationForm::class)
            ->call('enableTwoFactorAuthentication')
            ->call('regenerateRecoveryCodes');

        $user = $user->fresh();

        $component->call('regenerateRecoveryCodes');

        $this->assertCount(8, $user->recoveryCodes());
        $this->assertCount(8, array_diff($user->recoveryCodes(), $user->fresh()->recoveryCodes()));
    }

    /** @test */
    public function two_factor_authentication_can_be_disabled(): void
    {
        $this->actingAs($user = User::factory()->create());

        $this->withSession(['auth.password_confirmed_at' => time()]);

        $component = Livewire::test(TwoFactorAuthenticationForm::class)
            ->call('enableTwoFactorAuthentication');

        $this->assertNotNull($user->fresh()->two_factor_secret);

        $component->call('disableTwoFactorAuthentication');

        $this->assertNull($user->fresh()->two_factor_secret);
    }
}
