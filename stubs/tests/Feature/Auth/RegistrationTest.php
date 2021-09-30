<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Rawilk\LaravelBase\Features;
use Rawilk\LaravelBase\Http\Livewire\Auth\Register;
use Tests\TestCase;

final class RegistrationTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function can_view_the_registration_screen(): void
    {
        $this->skipIfNotEnabled();

        $this->get(route('register'))
            ->assertSuccessful()
            ->assertSeeLivewire('register');
    }

    /** @test */
    public function is_redirected_if_already_logged_in(): void
    {
        $this->skipIfNotEnabled();

        $this->actingAs(User::factory()->create())
            ->get(route('register'))
            ->assertRedirect(config('laravel-base.home'));
    }

    /** @test */
    public function users_can_register(): void
    {
        $this->skipIfNotEnabled();

        Event::fake();

        Livewire::test(Register::class)
            ->set('name', 'John Smith')
            ->set('email', 'john@example.com')
            ->set('password', 'secret')
            ->call('register')
            ->assertRedirect(config('laravel-base.home'));

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'first_name' => 'John',
            'last_name' => 'Smith',
        ]);
        $this->assertEquals('john@example.com', Auth::user()->email);

        Event::assertDispatched(Registered::class);
    }

    /** @test */
    public function name_is_required(): void
    {
        $this->skipIfNotEnabled();

        Livewire::test(Register::class)
            ->set('name', '')
            ->set('email', 'email@example.com')
            ->set('password', 'secret')
            ->call('register')
            ->assertHasErrors(['name' => 'required']);
    }

    /** @test */
    public function email_is_required(): void
    {
        $this->skipIfNotEnabled();

        Livewire::test(Register::class)
            ->set('name', 'John Smith')
            ->set('email', '')
            ->set('password', 'secret')
            ->call('register')
            ->assertHasErrors(['email' => 'required']);
    }

    /** @test */
    public function email_must_be_a_valid_email(): void
    {
        $this->skipIfNotEnabled();

        Livewire::test(Register::class)
            ->set('name', 'John Smith')
            ->set('email', 'foo')
            ->set('password', 'secret')
            ->call('register')
            ->assertHasErrors(['email' => 'email']);
    }

    /** @test */
    public function email_must_be_unique(): void
    {
        User::factory()->create(['email' => 'email@example.com']);

        $this->skipIfNotEnabled();

        Livewire::test(Register::class)
            ->set('name', 'John Smith')
            ->set('email', 'email@example.com')
            ->set('password', 'secret')
            ->call('register')
            ->assertHasErrors(['email' => 'unique']);
    }

    /** @test */
    public function password_is_required(): void
    {
        $this->skipIfNotEnabled();

        Livewire::test(Register::class)
            ->set('name', 'John Smith')
            ->set('email', 'email@example.com')
            ->set('password', '')
            ->call('register')
            ->assertHasErrors(['password' => 'required']);
    }

    private function skipIfNotEnabled(): void
    {
        if (! Features::usersCanRegister()) {
            $this->markTestSkipped('Registration is not enabled.');
        }
    }
}
