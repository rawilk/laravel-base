<?php

declare(strict_types=1);

use Rawilk\LaravelBase\Csp\PolicyFactory;
use Rawilk\LaravelBase\Exceptions\MissingCspMetaTagPolicy;

if (! function_exists('csp_nonce')) {
    function csp_nonce(): string
    {
        return app('csp-nonce');
    }
}

if (! function_exists('cspMetaTag')) {
    function cspMetaTag(string $policyClass): string
    {
        if ($policyClass === '') {
            throw MissingCspMetaTagPolicy::create();
        }

        if (! config('csp.enabled')) {
            return '';
        }

        $policy = PolicyFactory::create($policyClass);

        return "<meta http-equiv=\"{$policy->prepareHeader()}\" content=\"{$policy->__toString()}\">";
    }
}
