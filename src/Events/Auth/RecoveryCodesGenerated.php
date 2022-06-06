<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Events\Auth;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Queue\SerializesModels;

class RecoveryCodesGenerated
{
    use SerializesModels;

    public function __construct(public User $user)
    {
    }
}
