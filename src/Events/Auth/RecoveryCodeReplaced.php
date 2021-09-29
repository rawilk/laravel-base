<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Events\Auth;

use Illuminate\Queue\SerializesModels;

class RecoveryCodeReplaced
{
    use SerializesModels;

    public function __construct(public $user, public string $code)
    {
    }
}
