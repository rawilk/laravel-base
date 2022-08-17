<?php

declare(strict_types=1);

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\post;
use Rawilk\LaravelBase\Components\Alerts\Alert;
use Rawilk\LaravelBase\Concerns\Impersonatable;
use Rawilk\LaravelBase\Contracts\Models\ImpersonatesUsers;
use Rawilk\LaravelBase\Events\Auth\UserWasImpersonated;

beforeEach(function () {
    config(['auth.providers.users.model' => User::class]);

    $migration = require __DIR__ . '/../../Fixtures/database/migrations/create_users_table.php';
    (new $migration)->up();

    $this->sessionKey = config('laravel-base.impersonation.session_key');
    $this->rememberSessionKey = config('laravel-base.impersonation.remember_session_key');
    $this->nameSessionKey = config('laravel-base.impersonation.name_session_key');

    $this->impersonator = app(ImpersonatesUsers::class);

    // Prevent "Session store not set on request" error.
    $session = new ReflectionProperty(app('request'), 'session');
    $session->setAccessible(true);
    $session->setValue(app('request'), app('session')->driver('array'));
});

it('can impersonate users', function () {
    $admin = adminUser();
    $user = normalUser();

    actingAs($admin)
        ->post(route('admin.impersonate', ['userId' => $user->id]))
        ->assertSuccessful();

    expect(Auth::check())
        ->toBeTrue()
        ->and(Auth::user()->getAuthIdentifier())
        ->toBe($user->getAuthIdentifier())
        ->and(Session::has($this->sessionKey))
        ->toBeTrue()
        ->and(Session::get($this->sessionKey))
        ->toEqual($admin->getAuthIdentifier())
        ->and(Session::has($this->nameSessionKey))
        ->toBeTrue()
        ->and(Session::get($this->nameSessionKey))
        ->toEqual($admin->name);
});

it('can leave impersonation', function () {
    $admin = adminUser();
    $user = normalUser();

    actingAs($admin)
        ->post(route('admin.impersonate', ['userId' => $user->id]));

    delete(route('admin.impersonate.leave'))
        ->assertSuccessful();

    expect(Auth::user()->getAuthIdentifier())
        ->toBe($admin->getAuthIdentifier())
        ->and(Session::has($this->sessionKey))
        ->toBeFalse()
        ->and(Session::has($this->nameSessionKey))
        ->toBeFalse()
        ->and(Session::has($this->rememberSessionKey))
        ->toBeFalse();
});

it('can verify impersonation status', function () {
    expect($this->impersonator->impersonating(request()))
        ->toBeFalse();

    startImpersonation();

    expect($this->impersonator->impersonating(request()))
        ->toBeTrue();
});

it('gets the current impersonator name and id', function () {
    startImpersonation($user = adminUser());

    expect($this->impersonator->impersonatorName(request()))
        ->toEqual($user->name)
        ->and($this->impersonator->impersonatorId(request()))
        ->toBe($user->id);
});

it('prevents you from impersonating yourself', function () {
    $admin = adminUser();

    actingAs($admin)
        ->post(route('admin.impersonate', ['userId' => $admin->id]))
        ->assertSuccessful();

    expect($this->impersonator->impersonating(request()))
        ->toBeFalse()
        ->and(Session::has($this->sessionKey))
        ->toBeFalse();
});

test('logic can be put in place to prevent some users from being impersonated', function () {
    $admin = adminUser();
    $unImpersonatable = User::create(['name' => 'do not impersonate me', 'email' => 'email@example.test']);

    actingAs($admin)
        ->post(route('admin.impersonate', ['userId' => $unImpersonatable->id]))
        ->assertForbidden()
        ->assertSeeText(__('base::users.impersonate.cannot_impersonate_user'));
});

test('logic can be put in place to prevent users from impersonating other users', function () {
    $user = normalUser();
    $other = User::create(['name' => 'foo', 'email' => 'email@test.com', 'is_admin' => false]);

    actingAs($user)
        ->post(route('admin.impersonate', ['userId' => $other->id]))
        ->assertForbidden()
        ->assertSeeText(__('base::users.impersonate.cannot_impersonate_others'));
});

it('ends impersonation if you try impersonating another user at the same time', function () {
    $admin = adminUser();
    $otherAdmin = User::create(['name' => 'other admin', 'email' => 'other_admin@email.test', 'is_admin' => true]);
    $thirdUser = User::create(['name' => 'third', 'email' => 'third@email.test', 'is_admin' => false]);

    startImpersonation($admin, $otherAdmin);

    post(route('admin.impersonate', ['userId' => $thirdUser]))
        ->assertSuccessful();

    expect($this->impersonator->impersonating(request()))
        ->toBeFalse()
        ->and(Auth::user()->getAuthIdentifier())
        ->toBe($admin->getAuthIdentifier())
        ->and(Session::get(Alert::WARNING))
        ->toEqual(__('base::users.impersonate.only_one_allowed_alert'));
});

it('dispatches an event when impersonation is successful', function () {
    Event::fake();

    $admin = adminUser();
    $user = normalUser();

    startImpersonation($admin, $user);

    Event::assertDispatched(UserWasImpersonated::class, function ($event) use ($admin, $user) {
        return $event->impersonator->id === $admin->id && $event->user->id === $user->id;
    });
});

it('clears impersonate session on logout', function () {
    $admin = adminUser();
    $user = normalUser();

    startImpersonation($admin, $user);

    expect(Session::has($this->sessionKey))
        ->toBeTrue();

    event(new Logout('web', $user));

    expect(Session::has($this->sessionKey))
        ->toBeFalse();
});

class User extends \Illuminate\Foundation\Auth\User
{
    use Impersonatable;

    protected $guarded = [];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    public function canBeImpersonated(?User $impersonator = null): bool
    {
        return $this->name !== 'do not impersonate me';
    }

    public function canImpersonate(): bool
    {
        return $this->is_admin;
    }
}

// Helpers

function adminUser(): User
{
    return User::create(['name' => 'Admin User', 'email' => 'admin@example.com', 'is_admin' => true]);
}

function normalUser(): User
{
    return User::create(['name' => 'John Smith', 'email' => 'john@email.com', 'is_admin' => false]);
}

function startImpersonation(?User $impersonator = null, ?User $toImpersonate = null): void
{
    $impersonator = $impersonator ?: adminUser();
    $toImpersonate = $toImpersonate ?: normalUser();

    test()->actingAs($impersonator)
        ->post(route('admin.impersonate', ['userId' => $toImpersonate->id]));
}
