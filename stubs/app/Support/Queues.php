<?php

declare(strict_types=1);

namespace App\Support;

final class Queues
{
    public static function default(): string
    {
        return 'default';
    }

    public static function mail(): string
    {
        return 'mail';
    }
}
