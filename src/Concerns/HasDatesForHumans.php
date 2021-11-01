<?php

namespace Rawilk\LaravelBase\Concerns;

use Carbon\CarbonInterface;
use Illuminate\Support\Str;

/**
 * @property null|string $created_at_for_humans
 * @property null|string $updated_at_for_humans
 *
 * @mixin \Eloquent
 */
trait HasDatesForHumans
{
    public function getCreatedAtForHumansAttribute(): null|string
    {
        if (! ($date = $this->{$this->getCreatedAtColumn()})) {
            return null;
        }

        return $this->getDateTimeForHumans($date);
    }

    public function getUpdatedAtForHumansAttribute(): null|string
    {
        if (! ($date = $this->{$this->getUpdatedAtColumn()})) {
            return null;
        }

        return $this->getDateTimeForHumans($date);
    }

    protected function dateTimeForHumansFormat(): string
    {
        return property_exists($this, 'dateTimeForHumansFormat')
            ? $this->dateTimeForHumansFormat
            : 'M. d, Y g:i a';
    }

    protected function getDateTimeForHumans(null|CarbonInterface $date): null|string
    {
        if (! $date) {
            return null;
        }

        return $date->tz(userTimezone())->format($this->dateTimeForHumansFormat());
    }

    protected function isForHumansDateAttribute(string $attribute): bool
    {
        $methodName = 'get' . Str::studly($attribute) . 'Attribute';
        if (method_exists($this, $methodName)) {
            return false;
        }

        return Str::endsWith($attribute, '_for_humans')
            && $this->getAttribute(Str::beforeLast($attribute, '_for_humans'))
            && $this->isDateAttribute(Str::beforeLast($attribute, '_for_humans'));
    }

    public function __get($key)
    {
        if ($this->isForHumansDateAttribute($key)) {
            return $this->getDateTimeForHumans(
                $this->getAttribute(Str::beforeLast($key, '_for_humans'))
            );
        }

        return parent::__get($key);
    }
}
