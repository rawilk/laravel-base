<?php

namespace Rawilk\LaravelBase\Services\Files\Concerns;

use Rawilk\LaravelBase\Services\Files\Filesize;

trait PerformsArithmetic
{
    public function add(...$sizes): self
    {
        collect($sizes)->flatten()->each(fn ($size) => $this->addSize($size));

        $this->size = $this->sizeFromBytes();

        return $this;
    }

    public function subtract(...$sizes): self
    {
        collect($sizes)->flatten()->each(fn ($size) => $this->subtractSize($size));

        $this->size = $this->sizeFromBytes();

        return $this;
    }

    public function multiplyBy(string|int|float $multiplier): self
    {
        $this->sizeInBytes = bcmul($this->sizeInBytes, (string) $multiplier, $this->precision);
        $this->size = $this->sizeFromBytes();

        return $this;
    }

    public function divideBy(string|int|float $divisor): self
    {
        if (bccomp((string) $divisor, '0') === 0) {
            return $this;
        }

        $this->sizeInBytes = bcdiv($this->sizeInBytes, (string) $divisor, $this->precision);
        $this->size = $this->sizeFromBytes();

        return $this;
    }

    protected function addSize(string|Filesize $size): void
    {
        $this->sizeInBytes = bcadd($this->sizeInBytes, $this->sizeToBytes($size), $this->precision);
    }

    protected function subtractSize(string|Filesize $size): void
    {
        $this->sizeInBytes = bcsub($this->sizeInBytes, $this->sizeToBytes($size), $this->precision);
    }

    protected function sizeToBytes(string|Filesize $size): string
    {
        if ($size instanceof Filesize) {
            return $size->bytes();
        }

        // If no unit is specified in the string, we'll just assume that it's bytes.
        return Filesize::of($size)->bytes();
    }
}
