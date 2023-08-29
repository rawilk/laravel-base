<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Rawilk\LaravelBase\LaravelBase;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    LaravelBase::$findAppTimezoneUsingCallback = null;
    LaravelBase::$findUserTimezoneUsingCallback = null;
});

test('minDateToUTC() converts correctly', function () {
    $user = new TestUser(['timezone' => 'America/Chicago']);

    $expected = Carbon::parse('2021-09-01 05:00:00');

    actingAs($user)
        ->expect($expected->equalTo(minDateToUTC('2021-09-01 23:00:00')))->toBeTrue();
});

test('maxDateToUTC() converts correctly', function () {
    $user = new TestUser(['timezone' => 'America/Chicago']);

    actingAs($user);

    $expected = Carbon::parse('2021-09-02 04:59:59');
    $converted = maxDateToUTC('2021-09-01');

    /*
     * For some reason, Carbon's equalTo() is returning false, so we will
     * compare the values a different way in this test...
     */

    expect($converted->timestamp)
        ->toBe($expected->timestamp)
        ->and($converted->timezone)
        ->toEqual('UTC');
});

test('appTimezone() gets the configured timezone', function () {
    expect(appTimezone())->toEqual('UTC');

    config(['app.timezone' => 'America/Chicago']);

    expect(appTimezone())->toEqual('America/Chicago');
});

test('appTimezone() can use a custom callback to get the application timezone', function () {
    LaravelBase::findAppTimezoneUsing(fn () => 'America/New_York');

    expect(appTimezone())->toEqual('America/New_York');
});

test("userTimezone() gets the authenticated user's timezone", function () {
    $user = new TestUser(['timezone' => 'America/Chicago']);
    config(['app.timezone' => 'UTC']);

    // Before we are logged in, the app timezone should be returned as a fallback value.
    expect(userTimezone())->toEqual('UTC');

    actingAs($user)
        ->expect(userTimezone())->toEqual('America/Chicago');
});

test("userTimezone() can use a custom callback to find the user's timezone", function () {
    $user = new TestUser(['tz' => 'America/New_York']);
    config(['app.timezone' => 'UTC']);

    actingAs($user);

    LaravelBase::findUserTimezoneUsing(fn ($user) => $user->tz);

    expect(userTimezone())->toEqual('America/New_York');

    // userTimezone() should still fallback if no timezone is returned.
    LaravelBase::findUserTimezoneUsing(fn () => null);

    expect(userTimezone())->toEqual('UTC');
});

it('can convert empty strings to null', function () {
    $data = [
        'foo' => 'bar',
        'bool_value' => true,
        'int_value' => 0,
        'empty_value' => '',
        'value_with_space' => ' ',
    ];

    $expected = [
        'foo' => 'bar',
        'bool_value' => true,
        'int_value' => 0,
        'empty_value' => null,
        'value_with_space' => ' ',
    ];

    expect(convertEmptyStringsToNull($data))->toBe($expected);
});

class TestUser extends User
{
    protected $guarded = [];
}
