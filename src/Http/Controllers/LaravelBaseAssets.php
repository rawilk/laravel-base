<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Controllers;

use Illuminate\Support\Facades\File;
use Livewire\Controllers\CanPretendToBeAFile;

final class LaravelBaseAssets
{
    use CanPretendToBeAFile;

    public function source(string $asset)
    {
        $path = __DIR__ . "/../../../dist/assets/{$asset}";

        if (! File::exists($path)) {
            return '';
        }

        return $this->pretendResponseIsFile(__DIR__ . "/../../../dist/assets/{$asset}");
    }
}
