<?php

declare(strict_types=1);

namespace Tests\Feature\Profile;

use App\Models\User\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Rawilk\LaravelBase\Http\Livewire\Profile\UpdatePasswordForm;
use Tests\TestCase;

final class UpdatePasswordTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function password_can_be_updated(): void
    {
        $this->actingAs($user = User::factory()->create(['password' => 'secret']));

        Livewire::test(UpdatePasswordForm::class)
            ->set('state', [
                'current_password' => 'secret',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->call('updatePassword');

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    /** @test */
    public function current_password_must_be_correct(): void
    {
        $this->actingAs($user = User::factory()->create(['password' => 'secret']));

        Livewire::test(UpdatePasswordForm::class)
            ->set('state', [
                'current_password' => 'wrong-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->call('updatePassword')
            ->assertHasErrors(['current_password']);

        $this->assertTrue(Hash::check('secret', $user->fresh()->password));
    }

    /** @test */
    public function new_password_must_be_confirmed(): void
    {
        $this->actingAs($user = User::factory()->create(['password' => 'secret']));

        Livewire::test(UpdatePasswordForm::class)
            ->set('state', [
                'current_password' => 'secret',
                'password' => 'new-password',
                'password_confirmation' => 'not-match',
            ])
            ->call('updatePassword')
            ->assertHasErrors(['password' => 'confirmed']);

        $this->assertTrue(Hash::check('secret', $user->fresh()->password));
    }

    /** @test */
    public function new_password_is_required(): void
    {
        $this->actingAs($user = User::factory()->create(['password' => 'secret']));

        Livewire::test(UpdatePasswordForm::class)
            ->set('state', [
                'current_password' => 'secret',
                'password' => '',
                'password_confirmation' => '',
            ])
            ->call('updatePassword')
            ->assertHasErrors(['password' => 'required']);

        $this->assertTrue(Hash::check('secret', $user->fresh()->password));
    }
}
