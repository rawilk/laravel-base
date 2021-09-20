<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\DateTime;

use DateInterval;
use DateTimeInterface;
use Rawilk\LaravelBase\Components\BladeComponent;

class Countdown extends BladeComponent
{
    public function __construct(public DateTimeInterface $expires, public $onFinish = null)
    {
    }

    public function days(): string
    {
        return sprintf('%02d', $this->difference()->d);
    }

    public function hours(): string
    {
        return sprintf('%02d', $this->difference()->h);
    }

    public function minutes(): string
    {
        return sprintf('%02d', $this->difference()->i);
    }

    public function seconds(): string
    {
        return sprintf('%02d', $this->difference()->s);
    }

    public function difference(): DateInterval
    {
        return $this->expires->diff(now());
    }

    public function optionsToJson(): string
    {
        return json_encode([
            'days' => $this->days(),
            'hours' => $this->hours(),
            'minutes' => $this->minutes(),
            'seconds' => $this->seconds(),
            'expires' => $this->expires->timestamp,
        ]);
    }
}
