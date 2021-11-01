<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Actions\Roles;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Rawilk\LaravelBase\Events\Roles\RoleWasCreatedEvent;
use Rawilk\LaravelBase\Models\Role;

class CreateRoleAction
{
    public function __invoke(array $input)
    {
        $data = Validator::make($input, [
            'name' => [
                'required',
                'string',
                Rule::unique('roles'),
            ],
            'description' => ['nullable', 'string', 'max:' . Role::MAX_DESCRIPTION_LENGTH],
            'permissions' => ['array'],
        ])->validate();

        /** @var \Rawilk\LaravelBase\Models\Role $role */
        $role = app(config('permission.models.role'))::create(Arr::except($data, 'permissions'));

        if (! empty($data['permissions'])) {
            $role->givePermissionTo($data['permissions']);
        }

        event(new RoleWasCreatedEvent($role));
    }
}
