<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Policies;

use App\Models\User\User;
use App\Support\PermissionName;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Contracts\Role;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * A user can edit a role if they have permission to
     * edit roles, and that role is not the super admin
     * role.
     *
     * @param \App\Models\User\User $user
     * @param \Spatie\Permission\Contracts\Role $role
     * @return bool
     */
    public function edit(User $user, Role $role): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermissionTo(PermissionName::ROLES_EDIT)
            && ! $role->isSuperAdminRole();
    }

    /**
     * A user can edit a role's permissions if they have permission to
     * edit roles, and that role is not the super admin role.
     *
     * @param \App\Models\User\User $user
     * @param \Spatie\Permission\Contracts\Role $role
     * @return bool
     */
    public function editPermissions(User $user, Role $role): bool
    {
        return $user->hasPermissionTo(PermissionName::ROLES_EDIT)
            && ! $role->isSuperAdminRole();
    }

    /**
     * A user can delete a role if they have permission to
     * delete roles, adn the role isn't one of the
     * "protected" roles, i.e. "Super Admin", "Admin", "User".
     *
     * @param \App\Models\User\User $user
     * @param \Spatie\Permission\Contracts\Role $role
     * @return bool
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermissionTo(PermissionName::ROLES_DELETE)
            && ! $role->isProtected();
    }
}
