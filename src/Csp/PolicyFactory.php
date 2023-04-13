<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Csp;

use Rawilk\LaravelBase\Csp\Policies\CspPolicy;
use Rawilk\LaravelBase\Exceptions\InvalidCspPolicy;

/** @internal */
final class PolicyFactory
{
    public static function create(string $className): CspPolicy
    {
        $policy = app($className);

        if (! is_a($policy, CspPolicy::class, true)) {
            throw InvalidCspPolicy::create($policy);
        }

        if (! empty(config('csp.report_uri'))) {
            $policy->reportTo(config('csp.report_uri'));
        }

        return $policy;
    }
}
