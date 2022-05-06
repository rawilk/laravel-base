<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use Illuminate\Database\Seeder;
use Rawilk\LaravelBase\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $permissionModel = app(config('permission.models.permission'));

        foreach (PermissionEnum::cases() as $permission) {
            $permissionModel::updateOrCreate(['name' => $permission->value]);
        }

        // Update our "Admin" role if it exists to have all permissions.
        if ($role = app(config('permission.models.role'))->where('name', Role::$adminName)->first()) {
            $role->giveAllPermissions();
        }
    }
}
