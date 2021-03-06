<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Events\Auth;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Queue\SerializesModels;

class RecoveryCodeReplaced
{
    use SerializesModels;

    public function __construct(public User $user, public string $code)
    {
    }
}
