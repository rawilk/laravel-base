<?php

declare(strict_types=1);

it('will output the correct nonce', function () {
    $nonce = cspNonce();

    $view = app('view')
        ->file(__DIR__ . '/../fixtures/view.blade.php')
        ->render();

    expect(trim($view))->toBe('<script nonce="' . $nonce . '"></script>');
});
