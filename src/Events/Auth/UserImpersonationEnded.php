<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Events\Auth;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserImpersonationEnded
{
    use SerializesModels;
    use Dispatchable;

    public function __construct(public User $userBeingImpersonated, public User $impersonator)
    {
    }
}
