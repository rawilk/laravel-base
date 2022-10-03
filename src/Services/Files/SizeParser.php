<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Services\Files;

use InvalidArgumentException;
use Rawilk\LaravelBase\Enums\Filesize as FilesizeEnum;

class SizeParser
{
    /**
     * Pattern to match size strings, such as:
     *
     * - 150
     * - 10k
     * - 1,234 MB
     * - 1 gigabytes
     */
    protected const PATTERN = '/^(-?[0-9\., ]+)\s*([a-zA-Z]+)?$/';

    public static function parse($size): array
    {
        preg_match(self::PATTERN, $size, $matches);

        if (empty($matches[1])) {
            throw new InvalidArgumentException("Could not parse file size: {$size}");
        }

        return [
            'value' => $matches[1],
            'unit' => FilesizeEnum::fromUnit($matches[2] ?? FilesizeEnum::Byte),
        ];
    }
}
