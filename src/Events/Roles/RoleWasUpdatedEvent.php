<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Events\Roles;

use Illuminate\Queue\SerializesModels;
use Spatie\Permission\Contracts\Role;

class RoleWasUpdatedEvent
{
    use SerializesModels;

    public function __construct(public Role $role)
    {
    }
}
