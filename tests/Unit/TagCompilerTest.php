<?php

declare(strict_types=1);

use Rawilk\LaravelBase\Support\BaseTagCompiler;

beforeEach(function () {
    $this->compiler = new BaseTagCompiler;
});

it('compiles the scripts tag', function (string $tag) {
    $result = $this->compiler->compile($tag);

    expect('@lbJavaScript')->toBe($result);
})->with([
    '<lb:scripts />',
    '<lb:javaScript />',
]);
