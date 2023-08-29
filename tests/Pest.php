<?php

use Rawilk\LaravelBase\Tests\TestCase;

use function Orchestra\Testbench\artisan;

uses(TestCase::class)->in(__DIR__);

// Make sure view is compiled fresh each run in blade tests.
uses()->beforeEach(fn () => artisan($this, 'view:clear'))->in(
    'Csp/Blade',
);

expect()->extend('toHaveMetaContent', function ($value) {
    return expect($this->value)
        ->toMatch('/<meta http-equiv="[\w-]+" content="' . preg_quote($value) . '">/');
});

// Helpers
function withoutExceptionHandling(): void
{
    test()->withoutExceptionHandling();
}
