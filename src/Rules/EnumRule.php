<?php

namespace Rawilk\LaravelBase\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;
use Rawilk\LaravelBase\Contracts\Enums\HasLabel;

class EnumRule implements Rule
{
    protected $value;

    public function __construct(protected readonly string $enum)
    {
    }

    public function passes($attribute, $value): bool
    {
        $this->value = $value;

        if ($value instanceof $this->enum) {
            return true;
        }

        return (bool) $this->enum::tryFrom($value);
    }

    public function message(): string
    {
        return Lang::get('laravel-base::validation.enum', [
            'value' => $this->value,
            'enum' => $this->enum,
            'other' => implode(', ', $this->getDisplayableOtherValues()),
        ]);
    }

    protected function getDisplayableOtherValues(): array
    {
        return array_map(function ($case): string {
            if ($case instanceof HasLabel) {
                return $case->label();
            }

            return $case->name;
        }, $this->enum::cases());
    }
}
