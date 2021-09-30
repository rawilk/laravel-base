<?php

declare(strict_types=1);

namespace Tests\Feature\Profile;

use App\Models\User\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Rawilk\LaravelBase\Features;
use Rawilk\LaravelBase\Http\Livewire\Profile\DeleteUserForm;
use Tests\TestCase;

final class DeleteAccountTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function user_accounts_can_be_deleted(): void
    {
        $this->skipIfFeatureIsDisabled();

        $user = User::factory()->create(['password' => 'secret']);

        Livewire::actingAs($user)
            ->test(DeleteUserForm::class)
            ->set('password', 'secret')
            ->call('deleteUser');

        $this->assertNull($user->fresh());
        $this->assertGuest();
    }

    /** @test */
    public function correct_password_must_be_provided_to_delete_account(): void
    {
        $this->skipIfFeatureIsDisabled();

        $user = User::factory()->create(['password' => 'secret']);

        Livewire::actingAs($user)
            ->test(DeleteUserForm::class)
            ->set('password', 'wrong-password')
            ->call('deleteUser')
            ->assertHasErrors(['password']);

        $this->assertNotNull($user->fresh());
        $this->assertAuthenticatedAs($user);
    }

    private function skipIfFeatureIsDisabled(): void
    {
        if (! Features::hasAccountDeletionFeatures()) {
            $this->markTestSkipped('Account deletion is not enabled.');
        }
    }
}
