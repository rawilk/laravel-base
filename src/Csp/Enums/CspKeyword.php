<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Csp\Enums;

enum CspKeyword: string
{
    case None = 'none';
    case ReportSample = 'report-sample';
    case Self = 'self';
    case StrictDynamic = 'strict-dynamic';
    case UnsafeEval = 'unsafe-eval';
    case UnsafeHashes = 'unsafe-hashes';
    case UnsafeInline = 'unsafe-inline';
}
