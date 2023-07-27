<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Services\Files;

use BadMethodCallException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use JsonSerializable;
use Rawilk\LaravelBase\Enums\Filesize as FilesizeEnum;
use Rawilk\LaravelBase\Services\Files\Concerns\PerformsArithmetic;
use Stringable;

/**
 * Note on size values:
 *  - The sizes are as strings to allow parsing of large values without using scientific notation
 *    and because of that, we will use bcmath for mathematical operations.
 *
 * @method self asBytes()
 * @method self asKiloBytes()
 * @method self asMegaBytes()
 * @method self asGigaBytes()
 * @method self asTeraBytes()
 * @method self asPetaBytes()
 * @method self asExaBytes()
 * @method self asZettaBytes()
 * @method self asYottaBytes()
 */
class Filesize implements Stringable, Arrayable, JsonSerializable, Jsonable
{
    use PerformsArithmetic;
    use Macroable {
        __call as __callMacroable;
    }

    protected string $size;

    protected string $sizeInBytes;

    protected FilesizeEnum $unit;

    /*
     * Allows us to use the `withPrecision` fluent setter when
     * converting between units.
     */
    protected bool $precisionAlreadySet = false;

    protected string $system;

    protected null|bool|FilesizeEnum $preferredUnitFormat = null;

    public function __construct(string $size, string|FilesizeEnum $unit = null, protected int $precision = 3, string $system = 'binary')
    {
        $this->ensureValidSystem($system);

        $this->system = strtolower($system);

        $this->parseSize($size, $unit);
    }

    public static function of(string $size, string|FilesizeEnum $unit = null, int $precision = 3, string $system = 'binary'): self
    {
        return new self($size, $unit, $precision, $system);
    }

    public function asBinary(): self
    {
        $this->system = 'binary';

        $this->parseSize($this->size, $this->unit);

        return $this;
    }

    public function asDecimal(): self
    {
        $this->system = 'decimal';

        $this->parseSize($this->size, $this->unit);

        return $this;
    }

    protected function parseSize(string $size, string|FilesizeEnum $unit = null): void
    {
        $unit = $unit ?: FilesizeEnum::Byte;
        if (! $unit instanceof FilesizeEnum) {
            $unit = FilesizeEnum::fromUnit($unit);
        }

        if (is_numeric($size)) {
            $size = "{$size} {$unit->value}";
        }

        $parsed = SizeParser::parse($size);
        $this->size = $this->sanitizeParsedValue($parsed['value']);
        $this->unit = $parsed['unit'];

        $this->sizeInBytes = bcmul($this->size, $this->unit->conversionFactor($this->system), $this->precision);
    }

    public function value(): string
    {
        return $this->size;
    }

    public function bytes(): string
    {
        return $this->sizeInBytes;
    }

    public function unit(): FilesizeEnum
    {
        return $this->unit;
    }

    public function withPrecision(int $precision): self
    {
        $this->precision = $precision;
        $this->precisionAlreadySet = true;

        return $this;
    }

    public function as(string|FilesizeEnum $unit, int $precision = 3): self
    {
        $this->precision = $this->precisionAlreadySet
            ? $this->precision
            : $precision;
        $this->precisionAlreadySet = false;

        $converted = $this->convert($this->size, $this->unit, $unit);

        return new self($converted, $unit, $precision, $this->system);
    }

    public function convert(string $size, string|FilesizeEnum $fromUnit, string|FilesizeEnum $toUnit): string
    {
        $fromUnit = FilesizeEnum::fromUnit($fromUnit);
        $toUnit = FilesizeEnum::fromUnit($toUnit);

        if ($fromUnit !== $toUnit) {
            $fromFactor = $fromUnit->conversionFactor($this->system);
            $toFactor = $toUnit->conversionFactor($this->system);

            $size = bcdiv(bcmul($size, $fromFactor), $toFactor, $this->precision);
        }

        return $size;
    }

    public function format(): string
    {
        if (bccomp('0', $this->sizeInBytes) === 0) {
            return '0 ' . FilesizeEnum::Byte->value;
        }

        if ($this->preferredUnitFormat instanceof FilesizeEnum) {
            return $this->formatAs($this->preferredUnitFormat);
        }

        $base = $this->system === 'binary' ? '1024' : '1000';
        $bytes = Str::after($this->sizeInBytes, '-');

        for ($i = 0; bccomp(bcdiv($bytes, $base), '0.9') === 1; $i++) {
            $bytes = bcdiv($bytes, $base, $this->precision);
        }

        $rounded = round((float) $bytes, $this->precision);
        if (Str::startsWith($this->sizeInBytes, '-')) {
            $rounded = (float) "-{$rounded}";
        }

        // Determine how many decimals we actually need for `number_format`.
        $decimals = (int) strpos(strrev((string) $rounded), '.');

        $unit = app(SizeUnitFactor::class)->units()[$i] ?? FilesizeEnum::YottaByte->value;

        return number_format($rounded, $decimals) . ' ' . $unit;
    }

    public function formatAs(string|FilesizeEnum $unit): string
    {
        $unit = FilesizeEnum::fromUnit($unit);

        $size = $unit === $this->unit
            ? $this
            : $this->as($unit, $this->precision);

        $value = round((float) $size->value(), $this->precision);

        // Determine how many decimals we actually need for `number_format`.
        $decimals = (int) strpos(strrev((string) $value), '.');

        return number_format($value, $decimals) . ' ' . $size->unit()->value;
    }

    protected function sanitizeParsedValue(string $value): string
    {
        $chars = '0-9' . preg_quote('-.');

        return preg_replace("/[^{$chars}]/", '', $value);
    }

    protected function ensureValidSystem(string $system): void
    {
        throw_unless(
            in_array(strtolower($system), ['binary', 'decimal'], true),
            InvalidArgumentException::class,
            "Invalid system: {$system}",
        );
    }

    public function withPreferredFormat(FilesizeEnum $format): self
    {
        $this->preferredUnitFormat = $format;

        return $this;
    }

    public function withNoPreferredFormat(): self
    {
        $this->preferredUnitFormat = false;

        return $this;
    }

    protected function sizeFromBytes(string $bytes = null, FilesizeEnum $unit = null, int $precision = null, string $system = null): string
    {
        $bytes = $bytes ?: $this->sizeInBytes;
        $unit = $unit ?: $this->unit;
        $precision = $precision ?: $this->precision;
        $system = $system ?: $this->system;

        if (bccomp($bytes, '0') === 0) {
            return '0';
        }

        return bcdiv($bytes, $unit->conversionFactor($system), $precision);
    }

    public function toArray(): array
    {
        return [
            'size' => $this->size,
            'bytes' => $this->sizeInBytes,
            'unit' => $this->unit->value,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toJson($options = 0): string
    {
        return json_encode($this, $options);
    }

    public function __toString(): string
    {
        return $this->format();
    }

    public function __call(string $name, array $arguments)
    {
        if (! Str::startsWith($name, 'as')) {
            return $this->__callMacroable($name, $arguments);
        }

        $unitConversion = [
            'asBytes' => FilesizeEnum::Byte,
            'asKiloBytes' => FilesizeEnum::KiloByte,
            'asMegaBytes' => FilesizeEnum::MegaByte,
            'asGigaBytes' => FilesizeEnum::GigaByte,
            'asTeraBytes' => FilesizeEnum::TeraByte,
            'asPetaBytes' => FilesizeEnum::PetaByte,
            'asExaBytes' => FilesizeEnum::ExaByte,
            'asZettaBytes' => FilesizeEnum::ZettaByte,
            'asYottaBytes' => FilesizeEnum::YottaByte,
        ][$name] ?? null;

        throw_unless($unitConversion, BadMethodCallException::class, "Invalid conversion call: {$name}");

        $precision = $this->precisionAlreadySet
            ? $this->precision
            : $arguments[0] ?? 3;
        $this->precision = $precision;
        $this->precisionAlreadySet = false;

        $newSize = $this->as($unitConversion, $precision);

        if ($this->preferredUnitFormat !== false) {
            $newSize->withPreferredFormat($unitConversion);
        }

        return $newSize;
    }
}
