<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Imports\Roles;

use App\Enums\PermissionEnum;
use Illuminate\Support\Arr;
use Rawilk\LaravelBase\Imports\GeneralImport;
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

class RolesImport extends GeneralImport
{
    protected function processChunk(array $chunk): void
    {
        foreach ($chunk as $record) {
            $this->processRecord($record);
        }

        $this->incrementProcessedByChunkSize();
    }

    protected function processRecord(array $record): void
    {
        if (empty($record['name'])) {
            return;
        }

        if ($this->canUpdateOrCreate()) {
            $this->updateOrCreate($record);

            return;
        }

        if ($this->canCreate()) {
            $this->createOnly($record);

            return;
        }

        if ($this->canUpdate()) {
            $this->updateOnly($record);
        }
    }

    protected function updateOrCreate(array $data): void
    {
        ['permissions' => $permissions, 'data' => $data] = $this->normalizeData($data);

        $role = $this->model::withoutGlobalScopes()->updateOrCreate(['name' => $data['name']], Arr::except($data, 'name'));

        if (is_array($permissions) && $this->canEditPermissions($role)) {
            $role->syncPermissions($permissions);
        }
    }

    protected function createOnly(array $data): void
    {
        ['permissions' => $permissions, 'data' => $data] = $this->normalizeData($data);

        try {
            // An error will be thrown if the role already exists with the given name.
            $role = $this->model::create($data);
        } catch (RoleAlreadyExists) {
            return;
        }

        if (is_array($permissions)) {
            $role->givePermissionTo($permissions);
        }
    }

    protected function updateOnly(array $data): void
    {
        ['permissions' => $permissions, 'data' => $data] = $this->normalizeData($data);

        try {
            $role = $this->model::findByName($data['name']);
        } catch (RoleDoesNotExist) {
            return;
        }

        if ($this->canEditRole($role)) {
            $role->forceFill(Arr::except($data, 'name'))->save();

            if (is_array($permissions) && $this->canEditPermissions($role)) {
                $role->syncPermissions($permissions);
            }
        }
    }

    protected function canUpdateOrCreate(): bool
    {
        if (! $this->user) {
            return true;
        }

        return $this->user->hasAllPermissions([PermissionEnum::ROLES_CREATE->value, PermissionEnum::ROLES_EDIT->value]);
    }

    protected function canCreate(): bool
    {
        if (! $this->user) {
            return true;
        }

        return $this->user->can(PermissionEnum::ROLES_CREATE->value);
    }

    protected function canUpdate(): bool
    {
        if (! $this->user) {
            return true;
        }

        return $this->user->can(PermissionEnum::ROLES_EDIT->value);
    }

    protected function canEditRole(Role $role): bool
    {
        if (! $this->user) {
            return true;
        }

        return $this->user->can('edit', $role);
    }

    protected function canEditPermissions(Role $role): bool
    {
        if (! $this->user) {
            return true;
        }

        return $this->user->can('editPermissions', $role);
    }

    protected function normalizeData(array $data): array
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
