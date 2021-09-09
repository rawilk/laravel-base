<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Rawilk\LaravelBase\LaravelBase;

final class HelpersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        LaravelBase::$findAppTimezoneUsingCallback = null;
        LaravelBase::$findUserTimezoneUsingCallback = null;
    }

    /** @test */
    public function minDateToUTC_converts_correctly(): void
    {
        $user = new TestUser(['timezone' => 'America/Chicago']);

        $expected = Carbon::parse('2021-09-01 05:00:00');

        $this->actingAs($user)
            ->assertTrue($expected->equalTo(minDateToUTC('2021-09-01 23:00:00')));
    }

    /** @test */
    public function maxDateToUTC_converts_correctly(): void
    {
        $user = new TestUser(['timezone' => 'America/Chicago']);

        $this->actingAs($user);
        $expected = Carbon::parse('2021-09-02 04:59:59');
        $converted = maxDateToUTC('2021-09-01');

        // For some reason, carbon's equalTo() is returning false, so we will compare
        // it a different way here...
        $this->assertSame($expected->timestamp, $converted->timestamp);
        $this->assertEquals('UTC', $converted->timezone);
    }

    /** @test */
    public function appTimezone_gets_the_configured_timezone(): void
    {
        $this->assertEquals('UTC', appTimezone());

        config(['app.timezone' => 'America/Chicago']);

        $this->assertEquals('America/Chicago', appTimezone());
    }

    /** @test */
    public function appTimezone_can_use_a_custom_callback_to_get_the_application_timezone(): void
    {
        $callback = function () {
            return 'America/New_York';
        };

        LaravelBase::findAppTimezoneUsing($callback);

        $this->assertEquals('America/New_York', appTimezone());
    }

    /** @test */
    public function userTimezone_gets_the_authenticated_users_timezone(): void
    {
        $user = new TestUser(['timezone' => 'America/Chicago']);
        config(['app.timezone' => 'UTC']);

        // Before we are logged in, the app timezone should be returned as a fallback value.
        $this->assertEquals('UTC', userTimezone());

        $this->actingAs($user);

        $this->assertEquals('America/Chicago', userTimezone());
    }

    /** @test */
    public function userTimezone_can_use_a_custom_callback_to_find_the_users_timezone(): void
    {
        $user = new TestUser(['tz' => 'America/New_York']);
        config(['app.timezone' => 'UTC']);
        $this->actingAs($user);

        $callback = function ($user) {
            return $user->tz;
        };

        LaravelBase::findUserTimezoneUsing($callback);

        $this->assertEquals('America/New_York', userTimezone());

        // userTimezone should still fallback on the appTimezone if no timezone is returned.
        LaravelBase::findUserTimezoneUsing(fn () => null);

        $this->assertEquals('UTC', userTimezone());
    }

    /** @test */
    public function can_convert_empty_strings_to_null(): void
    {
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

        $this->assertSame($expected, convertEmptyStringsToNull($data));
    }

    /** @test */
    public function can_prefix_a_list_of_columns_with_a_models_table(): void
    {
        $expected = 'test_users.name,test_users.email,test_users.timezone';

        $this->assertEquals(
            $expected,
            prefixSelectColumns(TestUser::class, 'name', 'email', 'timezone')
        );
    }
}

class TestUser extends User
{
    protected $guarded = [];
}
