<?php

namespace Rawilk\LaravelBase\Concerns\Exports;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

trait FormatsColumns
{
    public function columnFormats(): array
    {
        $formats = [];

        foreach ($this->formattableColumns() as $index => $column) {
            if (! $format = $this->columnFormat($column)) {
                continue;
            }

            if ($letter = $this->columnLetter($index)) {
                $formats[$letter] = $format;
            }
        }

        return $formats;
    }

    protected function columnLetter(int $index): string
    {
        return Coordinate::stringFromColumnIndex($index + 1);
    }

    protected function columnFormat(string $column): ?string
    {
        if ($this->isDateTimeColumn($column)) {
            return NumberFormat::FORMAT_DATE_YYYYMMDD . ' ' . NumberFormat::FORMAT_DATE_TIME4;
        }

        return null;
    }

    protected function dateTimeColumns(): array
    {
        return [];
    }

    protected function formattableColumns(): array
    {
        return property_exists($this, 'columns')
            ? $this->columns
            : [];
    }

    protected function isDateTimeColumn(string $column): bool
    {
        if (in_array($column, ['created_at', 'updated_at'], true)) {
            return true;
        }

        return in_array($column, $this->dateTimeColumns(), true);
    }
}
