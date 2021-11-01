<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Support\PermissionName;
use Illuminate\Database\Seeder;
use Rawilk\LaravelBase\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $permissionModel = app(config('permission.models.permission'));

        foreach ($this->getPermissions() as $permission) {
            $permissionModel::updateOrCreate(['name' => $permission['name']], [
                'description' => $permission['description'],
            ]);
        }

        // Update our "Admin" role if it exists to have all of the permissions.
        if ($role = app(config('permission.models.role'))->where('name', Role::$adminName)->first()) {
            $role->giveAllPermissions();
        }
    }

    protected function getPermissions(): array
    {
        return [
            // Permissions...
            ['name' => PermissionName::PERMISSIONS_ASSIGN, 'description' => 'User can assign permissions to directly to other users.'],

            // Roles...
            ['name' => PermissionName::ROLES_CREATE, 'description' => 'User can create new roles.'],
            ['name' => PermissionName::ROLES_EDIT, 'description' => 'User can edit existing roles.'],
            ['name' => PermissionName::ROLES_DELETE, 'description' => 'User can delete roles.'],
            ['name' => PermissionName::ROLES_ASSIGN, 'description' => 'User can assign roles to other users.'],

            // Users...
            ['name' => PermissionName::USERS_CREATE, 'description' => 'User can create other user accounts.'],
            ['name' => PermissionName::USERS_EDIT, 'description' => 'User can edit other user accounts.'],
            ['name' => PermissionName::USERS_DELETE, 'description' => 'User can delete other user accounts.'],
            ['name' => PermissionName::USERS_IMPERSONATE, 'description' => 'User can impersonate other user accounts.'],
        ];
    }
}
