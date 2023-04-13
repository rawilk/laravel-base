<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Csp\Enums;

enum CspScheme: string
{
    case Blob = 'blob:';
    case Data = 'data:';
    case Http = 'http:';
    case Https = 'https:';
    case Ws = 'ws:';
    case Wss = 'wss:';
}
