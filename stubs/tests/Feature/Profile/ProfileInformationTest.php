<?php

declare(strict_types=1);

namespace Tests\Feature\Profile;

use App\Models\User\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Rawilk\LaravelBase\Http\Livewire\Profile\UpdateProfileInformationForm;
use Tests\TestCase;

final class ProfileInformationTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function current_profile_information_is_available(): void
    {
        $this->actingAs($user = User::factory()->create());

        $component = Livewire::test(UpdateProfileInformationForm::class);

        $this->assertEquals($user->email, $component->state['email']);
        $this->assertEquals($user->name, $component->state['name']);
        $this->assertEquals($user->timezone, $component->state['timezone']);
    }

    /** @test */
    public function profile_information_can_be_updated(): void
    {
        $this->actingAs($user = User::factory()->create());

        Livewire::test(UpdateProfileInformationForm::class)
            ->set('state', ['name' => 'Test Name', 'email' => 'test@example.com', 'timezone' => 'GMT'])
            ->call('updateProfileInformation');

        $user = $user->fresh();

        $this->assertEquals('Test Name', $user->name->full);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals('GMT', $user->timezone);
    }
}
