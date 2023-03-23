<?php

namespace Rawilk\LaravelBase\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

class EnumRule implements Rule
{
    protected $value;

    protected ?array $onlyCases = null;

    public function __construct(protected readonly string $enum)
    {
    }

    public function onlyCases(array $cases): self
    {
        $this->onlyCases = $cases;

        return $this;
    }

    public function passes($attribute, $value): bool
    {
        $this->value = $value;

        if ($this->onlyCases) {
            return $this->passesForCases($value);
        }

        if ($value instanceof $this->enum) {
            return true;
        }

        return (bool) $this->enum::tryFrom($value);
    }

    public function message(): string
    {
        return Lang::get('base::validation.enum', [
            'value' => $this->value,
            'enum' => $this->enum,
            'other' => implode(', ', enumToLabels($this->enum)),
        ]);
    }

    private function passesForCases($value): bool
    {
        $case = $this->enum::tryFrom($value);

        if (! $case) {
            return false;
        }

        return in_array($case, $this->onlyCases, true);
    }
}
