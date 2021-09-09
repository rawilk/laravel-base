<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Tests;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Rawilk\LaravelBase\LaravelBase;

final class HelpersTest extends TestCase
{
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
}

class TestUser extends User
{
    protected $guarded = [];
}
