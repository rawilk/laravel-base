<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Csp\Policies;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Rawilk\LaravelBase\Csp\Enums\CspDirective;
use Rawilk\LaravelBase\Csp\Enums\CspKeyword;
use Rawilk\LaravelBase\Csp\Enums\CspScheme;
use Rawilk\LaravelBase\Csp\Enums\CspValue;
use Rawilk\LaravelBase\Exceptions\InvalidCspDirective;
use Rawilk\LaravelBase\Exceptions\InvalidCspValueSet;
use Symfony\Component\HttpFoundation\Response;

abstract class CspPolicy
{
    protected array $directives = [];

    protected bool $reportOnly = false;

    abstract public function configure(): void;

    public function addDirective(string|CspDirective $directive, string|array|bool|CspKeyword|CspScheme $values): self
    {
        $this->guardAgainstInvalidDirectives($directive);
        $this->guardAgainstInvalidValues(Arr::wrap($values));

        if ($directive instanceof CspDirective) {
            $directive = $directive->value;
        }

        if ($values === CspValue::NO_VALUE) {
            $this->directives[$directive][] = CspValue::NO_VALUE;

            return $this;
        }

        $values = array_filter(
            Arr::flatten(
                array_map(fn ($value) => explode(' ', is_string($value) ? $value : $value->value), Arr::wrap($values))
            )
        );

        if ($this->containsEnumValue(CspKeyword::None, $values)) {
            $this->directives[$directive] = [$this->sanitizeValue(CspKeyword::None)];

            return $this;
        }

        $this->directives[$directive] = array_filter($this->directives[$directive] ?? [], function ($value) {
            return $value !== $this->sanitizeValue(CspKeyword::None);
        });

        foreach ($values as $value) {
            $sanitizedValue = $this->sanitizeValue($value);

            if (! in_array($sanitizedValue, $this->directives[$directive] ?? [], true)) {
                $this->directives[$directive][] = $sanitizedValue;
            }
        }

        return $this;
    }

    public function reportOnly(): self
    {
        $this->reportOnly = true;

        return $this;
    }

    public function enforce(): self
    {
        $this->reportOnly = false;

        return $this;
    }

    public function reportTo(string $uri): self
    {
        $this->directives[CspDirective::Report->value] = [$uri];

        return $this;
    }

    public function shouldBeApplied(Request $request, Response $response): bool
    {
        return config('csp.enabled');
    }

    public function addNonceForDirective(string|CspDirective $directive): self
    {
        return $this->addDirective(
            $directive,
            "'nonce-" . app('csp-nonce') . "'",
        );
    }

    public function upgradeInsecureRequests(): self
    {
        return $this->addDirective(CspDirective::UpgradeInsecureRequests, CspValue::NO_VALUE);
    }

    public function blockAllMixedContent(): self
    {
        return $this->addDirective(CspDirective::BlockAllMixedContent, CspValue::NO_VALUE);
    }

    public function prepareHeader(): string
    {
        $this->configure();

        return $this->reportOnly
            ? 'Content-Security-Policy-Report-Only'
            : 'Content-Security-Policy';
    }

    public function applyTo(Response $response): void
    {
        $headerName = $this->prepareHeader();

        if ($response->headers->has($headerName)) {
            return;
        }

        $response->headers->set($headerName, (string) $this);
    }

    public function __toString(): string
    {
        return collect($this->directives)
            ->map(function (array $values, string $directive) {
                $valueString = implode(' ', $values);

                return empty($valueString) ? $directive : "{$directive} {$valueString}";
            })
            ->implode(';');
    }

    protected function isHash(string $value): bool
    {
        $acceptableHashingAlgorithms = [
            'sha256-',
            'sha384-',
            'sha512-',
        ];

        return Str::startsWith($value, $acceptableHashingAlgorithms);
    }

    protected function isKeyword(string $value): bool
    {
        return CspKeyword::tryFrom($value) !== null;
    }

    protected function sanitizeValue(string|CspKeyword|CspScheme $value): string
    {
        if (! is_string($value)) {
            $value = $value->value;
        }

        if ($this->isKeyword($value) || $this->isHash($value)) {
            return "'{$value}'";
        }

        return $value;
    }

    protected function guardAgainstInvalidDirectives(string|CspDirective $directive): void
    {
        if ($directive instanceof CspDirective) {
            return;
        }

        if (! CspDirective::tryFrom($directive)) {
            throw InvalidCspDirective::notSupported($directive);
        }
    }

    protected function guardAgainstInvalidValues(array $values): void
    {
        if ($this->containsEnumValue(CspKeyword::None, $values) && count($values) > 1) {
            throw InvalidCspValueSet::noneMustBeOnlyValue();
        }
    }

    /**
     * We are checking for either the presence of the enum instance or the enum value
     * to allow more flexibility when adding directives.
     */
    protected function containsEnumValue(CspDirective|CspKeyword $enum, array $values): bool
    {
        if (in_array($enum, $values, true)) {
            return true;
        }

        return in_array($enum->value, $values, true);
    }
}
