<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Enums;

use InvalidArgumentException;

enum Filesize: string
{
    case Byte = 'B';
    case KiloByte = 'KB';
    case MegaByte = 'MB';
    case GigaByte = 'GB';
    case TeraByte = 'TB';
    case PetaByte = 'PB';
    case ExaByte = 'EB';
    case ZettaByte = 'ZB';
    case YottaByte = 'YB';

    public static function fromUnit(string|self $unit): self
    {
        if ($unit instanceof self) {
            return $unit;
        }

        return match (strtolower($unit)) {
            'b', 'byte', 'bytes', => self::Byte,
            'k', 'kb', 'kilobyte', 'kilobytes' => self::KiloByte,
            'm', 'mb', 'megabyte', 'megabytes' => self::MegaByte,
            'g', 'gb', 'gigabyte', 'gigabytes' => self::GigaByte,
            't', 'tb', 'terabyte', 'terabytes' => self::TeraByte,
            'p', 'pb', 'petabyte', 'petabytes' => self::PetaByte,
            'e', 'eb', 'exabyte', 'exabytes' => self::ExaByte,
            'z', 'zb', 'zettabyte', 'zettabytes' => self::ZettaByte,
            'y', 'yb', 'yottabyte', 'yottabytes' => self::YottaByte,
            default => throw new InvalidArgumentException("Invalid file size unit: {$unit}"),
        };
    }

    public function conversionFactor(string $system = 'binary'): string
    {
        if ($system === 'decimal') {
            return $this->decimalConversionFactor();
        }

        return match ($this) {
            self::Byte => '1',
            self::KiloByte => bcpow('2', '10'),
            self::MegaByte => bcpow('2', '20'),
            self::GigaByte => bcpow('2', '30'),
            self::TeraByte => bcpow('2', '40'),
            self::PetaByte => bcpow('2', '50'),
            self::ExaByte => bcpow('2', '60'),
            self::ZettaByte => bcpow('2', '70'),
            self::YottaByte => bcpow('2', '80'),
        };
    }

    public function decimalConversionFactor(): string
    {
        return match ($this) {
            self::Byte => '1',
            self::KiloByte => bcpow('10', '3'),
            self::MegaByte => bcpow('10', '6'),
            self::GigaByte => bcpow('10', '9'),
            self::TeraByte => bcpow('10', '12'),
            self::PetaByte => bcpow('10', '15'),
            self::ExaByte => bcpow('10', '18'),
            self::ZettaByte => bcpow('10', '21'),
            self::YottaByte => bcpow('10', '24'),
        };
    }
}
