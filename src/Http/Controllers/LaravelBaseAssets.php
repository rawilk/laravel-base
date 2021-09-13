<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Controllers;

use Livewire\Controllers\CanPretendToBeAFile;

final class LaravelBaseAssets
{
    use CanPretendToBeAFile;

    public function source()
    {
        return $this->pretendResponseIsFile(__DIR__ . '/../../../dist/laravel-base.js');
    }

    public function maps()
    {
        return $this->pretendResponseIsFile(__DIR__ . '/../../../dist/laravel-base.js.map');
    }
}
