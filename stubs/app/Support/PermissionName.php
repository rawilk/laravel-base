<?php

declare(strict_types=1);

namespace App\Support;

final class PermissionName
{
    // Permissions...
    public const PERMISSIONS_ASSIGN = 'permissions.assign';

    // Roles...
    public const ROLES_CREATE = 'roles.create';
    public const ROLES_EDIT = 'roles.edit';
    public const ROLES_DELETE = 'roles.delete';
    public const ROLES_ASSIGN = 'roles.assign';

    // Users...
    public const USERS_CREATE = 'users.create';
    public const USERS_EDIT = 'users.edit';
    public const USERS_DELETE = 'users.delete';
    public const USERS_IMPERSONATE = 'users.impersonate';
}
