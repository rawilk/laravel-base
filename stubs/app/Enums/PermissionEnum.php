<?php

namespace App\Enums;

use RuntimeException;

enum PermissionEnum: string
{
    // Permissions...
    case PERMISSIONS_ASSIGN = 'permissions.assign';

    // Roles...
    case ROLES_CREATE = 'roles.create';
    case ROLES_EDIT = 'roles.edit';
    case ROLES_DELETE = 'roles.delete';
    case ROLES_ASSIGN = 'roles.assign';

    // Users...
    case USERS_CREATE = 'users.create';
    case USERS_EDIT = 'users.edit';
    case USERS_DELETE = 'users.delete';
    case USERS_IMPERSONATE = 'users.impersonate';

    public function description(): string
    {
        return match ($this) {
            // Permissions...
            self::PERMISSIONS_ASSIGN => __('User can assign permissions directly to other users.'),

            // Roles...
            self::ROLES_CREATE => __('User can create new roles.'),
            self::ROLES_EDIT => __('User can edit exiting roles.'),
            self::ROLES_DELETE => __('User can delete roles.'),
            self::ROLES_ASSIGN => __('User can assign roles to users.'),

            // Users...
            self::USERS_CREATE => __('User can create other user accounts.'),
            self::USERS_EDIT => __('User can edit other user accounts.'),
            self::USERS_DELETE => __('User can delete other user accounts.'),
            self::USERS_IMPERSONATE => __('User can impersonate other user accounts.'),

            // In the case we forget to add a description for a new case, we'll throw an exception to remind us...
            default => throw new RuntimeException("No description provided yet for: {$this->name}!"),
        };
    }
}
