<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Imports\Roles;

use App\Enums\PermissionEnum;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;
use Rawilk\LaravelBase\Concerns\Imports\MapsFields;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

class RolesImport implements WithHeadingRow, OnEachRow
{
    use Importable;
    use MapsFields;

    protected int $count = 0;

    public function count(): int
    {
        return $this->count;
    }

    public function onRow(Row $row): void
    {
        $data = $this->extractFieldsFromRow($row->toArray(), ['guard_name' => 'web']);

        if (empty($data['name'])) {
            return;
        }

        if (Auth::user()->hasAllPermissions([PermissionEnum::ROLES_CREATE->value, PermissionEnum::ROLES_EDIT->value])) {
            $this->updateOrCreate($data);

            return;
        }

        if (Auth::user()->can(PermissionEnum::ROLES_CREATE->value)) {
            $this->createOnly($data);

            return;
        }

        if (Auth::user()->can(PermissionEnum::ROLES_EDIT->value)) {
            $this->updateOnly($data);
        }
    }

    protected function createOnly(array $data): void
    {
        ['permissions' => $permissions, 'data' => $data] = $this->extractPermissionsFromData($data);

        try {
            // An error will be thrown if the role already exists with the given name.
            $role = app(config('permission.models.role'))->create($data);
        } catch (RoleAlreadyExists) {
            return;
        }

        if (is_array($permissions)) {
            $role->givePermissionTo($permissions);
        }

        $this->count++;
    }

    protected function updateOnly(array $data): void
    {
        ['permissions' => $permissions, 'data' => $data] = $this->extractPermissionsFromData($data);

        try {
            $role = app(config('permission.models.role'))::findByName($data['name'] ?? '');
        } catch (RoleDoesNotExist) {
            return;
        }

        if (Auth::user()->can('edit', $role)) {
            $role->forceFill(Arr::except($data, 'name'))->save();

            if (is_array($permissions) && Auth::user()->can('editPermissions', $role)) {
                $role->syncPermissions($permissions);
            }

            $this->count++;
        }
    }

    protected function updateOrCreate(array $data): void
    {
        ['permissions' => $permissions, 'data' => $data] = $this->extractPermissionsFromData($data);

        $role = app(config('permission.models.role'))::withoutGlobalScopes()->updateOrCreate(['name' => $data['name']], Arr::except($data, 'name'));

        if (is_array($permissions) && Auth::user()->can('editPermissions', $role)) {
            $role->syncPermissions($permissions);
        }

        $this->count++;
    }

    protected function extractPermissionsFromData(array $data): array
    {
        $permissions = Arr::get($data, 'permissions');

        if (is_string($permissions)) {
            $permissions = json_decode($permissions, true);
        }

        return [
            'permissions' => $permissions,
            'data' => Arr::except($data, 'permissions'),
        ];
    }
}
