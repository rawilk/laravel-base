<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Csp\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Rawilk\LaravelBase\Csp\PolicyFactory;

class AddCspHeaders
{
    public function handle(Request $request, Closure $next, string $customPolicyClass = null): mixed
    {
        $response = $next($request);

        $this
            ->getPolicies($customPolicyClass)
            ->filter->shouldBeApplied($request, $response)
            ->each->applyTo($response);

        return $response;
    }

    protected function getPolicies(string $customPolicyClass = null): Collection
    {
        $policies = collect();

        if ($customPolicyClass) {
            $policies->push(PolicyFactory::create($customPolicyClass));

            return $policies;
        }

        $policyClass = config('csp.policy');

        if (! empty($policyClass)) {
            $policy = PolicyFactory::create($policyClass);

            $reportOnly = config('csp.report_only');

            if ($reportOnly) {
                $policy->reportOnly();
            }

            $policies->push($policy);
        }

        return $policies;
    }
}
