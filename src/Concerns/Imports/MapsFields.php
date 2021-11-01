<?php

namespace Rawilk\LaravelBase\Concerns\Imports;

trait MapsFields
{
    protected array $columnMap = [];

    public function usingMap(array $map): self
    {
        $this->columnMap = $map;

        return $this;
    }

    protected function extractFieldsFromRow($row, array $defaults = []): array
    {
        $attributes = collect($this->columnMap)
            ->map(fn ($column) => trim($column))
            ->filter()
            ->mapWithKeys(function ($heading, $field) use ($row) {
                return [$field => $row[$heading] ?? ''];
            })
            ->toArray();

        return $attributes + $defaults;
    }
}
