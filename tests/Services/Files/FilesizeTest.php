<?php

declare(strict_types=1);

use Rawilk\LaravelBase\Enums\Filesize as FilesizeEnum;
use Rawilk\LaravelBase\Services\Files\Filesize;

it('parses file sizes correctly', function (string $fileSize, string $expectedBytes, string $expectedSize, FilesizeEnum $expectedUnit) {
    $size = Filesize::of($fileSize);

    $this->assertSame(
        0,
        bccomp($size->bytes(), $expectedBytes),
        "Actual size {$size->bytes()} not equal to expected {$expectedBytes}"
    );

    expect($size->value())->toBe($expectedSize)
        ->and($size->unit())->toBe($expectedUnit);
})->with([
    ['1 KB', '1024', '1', FilesizeEnum::KiloByte],
    ['1024 KB', '1048576', '1024', FilesizeEnum::KiloByte],
    ['1 MB', '1048576', '1', FilesizeEnum::MegaByte],
    ['1.56 GB', '1675037245.44', '1.56', FilesizeEnum::GigaByte],
    ['25 TB', '27487790694400', '25', FilesizeEnum::TeraByte],
    ['500 KiloBytes', '512000', '500', FilesizeEnum::KiloByte],
    ['1,000 Bytes', '1000', '1000', FilesizeEnum::Byte],
    ['1 PB', '1125899906842624', '1', FilesizeEnum::PetaByte],
    ['1 EB', '1152921504606846976', '1', FilesizeEnum::ExaByte],
    ['1 ZB', '1180591620717411303424', '1', FilesizeEnum::ZettaByte],
    ['1 YB', '1208925819614629174706176', '1', FilesizeEnum::YottaByte],
]);

it('parses decimal file sizes correctly', function (string $fileSize, string $expectedBytes, string $expectedSize, FilesizeEnum $expectedUnit) {
    $size = Filesize::of($fileSize, null, 3, 'decimal');

    $this->assertSame(
        0,
        bccomp($size->bytes(), $expectedBytes),
        "Actual size {$size->bytes()} not equal to expected {$expectedBytes}",
    );
})->with([
    ['1 KB', '1000', '1', FilesizeEnum::KiloByte],
    ['1024 KB', '1024000', '1024', FilesizeEnum::KiloByte],
    ['1 MB', '1000000', '1', FilesizeEnum::MegaByte],
    ['1.56 GB', '1560000000', '1.56', FilesizeEnum::GigaByte],
    ['25 TB', '25000000000000', '25', FilesizeEnum::TeraByte],
    ['500 KiloBytes', '500000', '500', FilesizeEnum::KiloByte],
    ['1,000 Bytes', '1000', '1000', FilesizeEnum::Byte],
    ['1 PB', '1000000000000000', '1', FilesizeEnum::PetaByte],
    ['1 EB', '1000000000000000000', '1', FilesizeEnum::ExaByte],
    ['1 ZB', '1000000000000000000000', '1', FilesizeEnum::ZettaByte],
    ['1 YB', '1000000000000000000000000', '1', FilesizeEnum::YottaByte],
]);

it('converts binary file sizes correctly', function (string $filesize, FilesizeEnum $newUnit, string $expectedSize) {
    $size = Filesize::of($filesize);

    $newSize = $size->withPrecision(30)->as($newUnit);

    expect(bccomp($newSize->value(), $expectedSize))->toBe(0)
        ->and(bccomp($size->bytes(), $newSize->bytes()))->toBe(0);
})->with([
    ['500 KB', FilesizeEnum::MegaByte, '0.4882812500'],
    ['8000 KB', FilesizeEnum::MegaByte, '7.8125000000'],
    ['8000 KB', FilesizeEnum::GigaByte, '0.0076293945'],
    ['8000 KB', FilesizeEnum::TeraByte, '0.0000074506'],
    ['2.5 TB', FilesizeEnum::GigaByte, '2560'],
    ['2.5 TB', FilesizeEnum::MegaByte, '2621440'],
]);

it('converts decimal file sizes correctly', function (string $filesize, FilesizeEnum $newUnit, string $expectedSize) {
    $size = Filesize::of($filesize)->withPrecision(30)->asDecimal();

    $newSize = $size->as($newUnit, 30);

    expect(bccomp($newSize->value(), $expectedSize))->toBe(0)
        ->and(bccomp($size->bytes(), $newSize->bytes()))->toBe(0);
})->with([
    ['500 KB', FilesizeEnum::MegaByte, '0.5'],
    ['1000 KB', FilesizeEnum::MegaByte, '1'],
    ['2.5 TB', FilesizeEnum::GigaByte, '2500'],
    ['2.5 TB', FilesizeEnum::MegaByte, '2500000'],
]);

it('formats binary sizes correctly', function (string $fileSize, string $expectedFormat) {
    $size = Filesize::of($fileSize);

    expect($size->format())->toBe($expectedFormat);
})->with([
    ['100 KB', '100 KB'],
    ['1023 KB', '1,023 KB'],
    ['1024 KB', '1 MB'],
    ['1.56 GB', '1.56 GB'],
    ['1024 GB', '1 TB'],
    ['0 TB', '0 B'],
    ['1 B', '1 B'],
    ['1000.245 TB', '1,000.245 TB'],
    ['1 EB', '1 EB'],
    ['1 YB', '1 YB'],
]);

it('formats decimal file sizes correctly', function (string $filesize, string $expectedFormat) {
    $size = Filesize::of($filesize)->asDecimal();

    expect($size->format())->toBe($expectedFormat);
})->with([
    ['1 B', '1 B'],
    ['1000 KB', '1 MB'],
    ['1 KB', '1 KB'],
    ['1 MB', '1 MB'],
    ['1024 KB', '1.024 MB'],
]);

it('formats file sizes for humans', function (string $filesize, string $expectedFormat) {
    $size = Filesize::of($filesize);

    expect($size->format())->toBe($expectedFormat);
})->with([
    ['1 B', '1 B'],
    ['1 KB', '1 KB'],
    ['1024 KB', '1 MB'],
    ['1 GB', '1 GB'],
    ['1 TB', '1 TB'],
    ['500.5 GB', '500.5 GB'],
]);

it('formats human friendly values for large values', function () {
    $size = Filesize::of('1234522678.12', FilesizeEnum::KiloByte);

    expect($size->withPrecision(2)->format())->toBe('1.14 TB');
});

it('formats human friendly values for small values', function () {
    $size = Filesize::of(bcdiv('1.2345', '3', 5), FilesizeEnum::KiloByte);

    expect($size->withPrecision(0)->format())->toBe('421 B');
});

it('will format a size as the converted to unit of measurement when using magic methods', function () {
    $size = Filesize::of('1024 KB');

    expect($size->asBytes()->format())->toBe('1,048,576 B');
});

it('will auto format after conversion if told to', function () {
    $size = Filesize::of('1024 KB');

    expect($size->withNoPreferredFormat()->asBytes()->format())->toBe('1 MB');
});

it('can format a file size as a desired format', function () {
    $size = Filesize::of('1024 KB')->withPrecision(20);

    expect($size->formatAs(FilesizeEnum::Byte))->toBe('1,048,576 B')
        ->and($size->formatAs(FilesizeEnum::KiloByte))->toBe('1,024 KB')
        ->and($size->formatAs(FilesizeEnum::GigaByte))->toBe('0.0009765625 GB');
});

it('supports the smallest integer', function () {
    $size = Filesize::of((string) PHP_INT_MIN, FilesizeEnum::Byte);

    $yottaBytes = (clone $size)->withPrecision(18)->asYottaBytes();

    expect(bccomp($size->value(), (string) PHP_INT_MIN))->toBe(0)
        ->and(bccomp($yottaBytes->bytes(), (string) PHP_INT_MIN))->toBe(0)
        ->and(bccomp($yottaBytes->value(), '-0.000007629394531250'))->toBe(0);
});

it('supports the largest integer', function () {
    $size = Filesize::of((string) PHP_INT_MAX, FilesizeEnum::YottaByte);

    expect(bccomp($size->bytes(), '11150372599265311569558933316709551578284032'))->toBe(0)
        ->and(bccomp($size->value(), (string) PHP_INT_MAX))->toBe(0);
});

it('can be cast to a string', function () {
    $size = Filesize::of('1024 KB');

    expect((string) $size)->toBe('1 MB');
});

it('can add sizes', function () {
    $size = Filesize::of('123 MB');
    $size->add('150 KB');

    expect(bccomp($size->bytes(), '129128448'))->toBe(0);
});

test('negative sizes can be added', function () {
    $size = Filesize::of('10 MB');
    $size->add('-20 MB');

    expect($size->format())->toBe('-10 MB');
});

test('multiple sizes can be added at the same time', function () {
    $size = Filesize::of('10 MB');
    $size->add('1 MB', '5 MB', '1 MB');

    expect($size->format())->toBe('17 MB');

    $size->add(['1 MB', '2 MB'], '1 MB');

    expect($size->format())->toBe('21 MB');
});

it('can subtract sizes', function () {
    $size = Filesize::of('123 MB');
    $size->subtract('150 KB');

    expect(bccomp($size->bytes(), '128821248'))->toBe(0);
});

test('negative sizes can be subtracted', function () {
    $size = Filesize::of('10 MB');
    $size->subtract('-20 MB');

    expect($size->format())->toBe('30 MB');
});

it('can multiply sizes', function () {
    $size = Filesize::of('425.51 MB');
    $size->multiplyBy(9.125);

    expect($size->withPrecision(2)->formatAs(FilesizeEnum::GigaByte))->toBe('3.79 GB');
});

it('can divide sizes', function () {
    $size = Filesize::of('300 KB');
    $size->divideBy(2);

    expect($size->formatAs(FilesizeEnum::KiloByte))->toBe('150 KB');
});

it('ignores division by zero', function () {
    $size = Filesize::of('300 KB');
    $size->divideBy(0);

    expect($size->format())->toBe('300 KB');
});

it('can be converted to an array', function () {
    $size = Filesize::of('1024 KB');

    expect($size->toArray())->toMatchArray([
        'size' => '1024',
        'bytes' => '1048576.000',
        'unit' => 'KB',
    ]);
});

it('can be converted to json', function () {
    $size = Filesize::of('1024 KB');

    $expectedJson = [
        'size' => '1024',
        'bytes' => '1048576.000',
        'unit' => 'KB',
    ];

    expect(json_encode($size))->json()->toMatchArray($expectedJson)
        ->and($size->toJson())->json()->toMatchArray($expectedJson);
});

test('custom functionality can be added to it', function () {
    Filesize::macro('sayHi', function () {
        return "My size is: {$this->format()}";
    });

    $size = Filesize::of('1 MB');

    expect($size->sayHi())->toBe('My size is: 1 MB');
});
