<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Rawilk\LaravelBase\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $roleModel = app(config('permission.models.role'));

        foreach ($this->getSeedableRoles() as $data) {
            if ($roleModel::withoutGlobalScopes()->where('name', $data['name'])->exists()) {
                continue;
            }

            $role = $roleModel::create($data);

            if ($role->name === Role::$adminName) {
                $role->giveAllPermissions();
            }
        }
    }

    protected function getSeedableRoles(): array
    {
        return [
            [
                'name' => Role::$superAdminName,
                'description' => 'The super admin role has unrestricted access to anything on the site.',
            ],
            [
                'name' => Role::$adminName,
                'description' => 'The administrator role has a high level of access to the site.',
            ],
            [
                'name' => Role::$userName,
                'description' => 'The basic user account role.',
            ],
        ];
    }
}
