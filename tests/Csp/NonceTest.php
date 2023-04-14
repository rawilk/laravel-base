<?php

declare(strict_types=1);

use Rawilk\LaravelBase\Csp\Nonce\RandomStringNonceGenerator;

it('will generate the same result', function () {
    $nonce = csp_nonce();

    foreach (range(1, 5) as $i) {
        expect(csp_nonce())->toBe($nonce);
    }
});

test('RandomStringNonceGenerator will generate a random string', function () {
    $nonce = app(RandomStringNonceGenerator::class)->generate();

    $this->assertEquals(strlen($nonce), 32);
});
