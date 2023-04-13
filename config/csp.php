<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Policy
    |--------------------------------------------------------------------------
    |
    | A policy will determine which CSP headers will be set. A valid CSP policy
    | is any class that extends `Rawilk\LaravelBase\Csp\Policies\CspPolicy`
    |
    */
    'policy' => \Rawilk\LaravelBase\Csp\Policies\BasicCsp::class,

    /*
    |--------------------------------------------------------------------------
    | Report Only
    |--------------------------------------------------------------------------
    |
    | The policy will be put into report only mode. This is great for testing
    | out a new policy or changes to an existing csp policy without breaking
    | anything.
    |
    */
    'report_only' => env('CSP_REPORT_ONLY', false),

    /*
    |--------------------------------------------------------------------------
    | Report URI
    |--------------------------------------------------------------------------
    |
    | All violations against the policy will be reported to this url.
    | A great service you could use for this is https://report-uri.com/
    |
    | You can override this setting by calling `reportTo` on your policy.
    |
    */
    'report_uri' => env('CSP_REPORT_URI'),

    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | CSP headers will only be added if this is set to true.
    |
    */
    'enabled' => env('CSP_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Nonce Generator
    |--------------------------------------------------------------------------
    |
    | This class is responsible for generating the nonces used in inline
    | tags and headers.
    |
    */
    'nonce_generator' => \Rawilk\LaravelBase\Csp\Nonce\ViteNonceGenerator::class,
];
