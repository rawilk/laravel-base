<?php

declare(strict_types=1);

use Symfony\Component\Finder\Finder;

$files = Finder::create()
    ->files()
    ->in(__DIR__)
    ->depth(0)
    ->name('*.php');

foreach ($files as $file) {
    require_once $file;
}
