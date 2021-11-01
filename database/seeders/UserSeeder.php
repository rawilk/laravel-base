<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User\User;
use Illuminate\Database\Seeder;
use Rawilk\LaravelBase\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->createSuperAdminUser();
    }

    protected function createSuperAdminUser(): void
    {
        // We've already seeded the admin user, no need to do it again.
        if (User::withoutGlobalScopes()->whereId(1)->exists()) {
            return;
        }

        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'secret',
            'remember_token' => null,
        ]);

        // Our "super admin" user needs to be assigned the "super admin" role.
        $role = app(config('permission.models.role'))->withoutGlobalScopes()->where('name', Role::$superAdminName)->first();

        if ($role) {
            $user->assignRole($role);
        }
    }
}
