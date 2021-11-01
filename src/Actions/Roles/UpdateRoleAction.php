<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Actions\Roles;

use Illuminate\Support\Facades\Validator;
use Rawilk\LaravelBase\Events\Roles\RoleWasUpdatedEvent;
use Rawilk\LaravelBase\Models\Role as RoleModel;
use Spatie\Permission\Contracts\Role;

class UpdateRoleAction
{
    public function __invoke(Role $role, array $input)
    {
        $data = Validator::make($input, [
            'description' => ['nullable', 'string', 'max:' . RoleModel::MAX_DESCRIPTION_LENGTH],
        ])->validate();

        $role->forceFill($data)->save();

        if ($role->wasChanged()) {
            event(new RoleWasUpdatedEvent($role));
        }
    }
}
