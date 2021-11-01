<?php

return [
    'alerts' => [
        'created' => 'Role was created successfully.',
        'bulk_deleted' => ':count role was deleted.|:count roles were deleted.',
        'deleted' => ':name was deleted!',
        'import_success' => 'Imported :count role.|Imported :count roles.',
        'no_results' => 'No roles found...',
        'permissions_cannot_be_modified' => 'The permissions of the ":name" role cannot be modified.',
    ],

    'confirm_delete' => [
        'title' => 'Delete Role',
        'text' => 'This will permanently delete the <strong>:role</strong> role! Please be sure you want to do this. This could affect any users assigned to this role from accessing certain parts of the site.',
    ],

    'confirm_bulk_delete' => [
        'title' => 'Delete Selected Roles',
        'text' => 'This will permanently delete the selected role(s). Please be sure you want to do this. This could affect any users assigned to the role(s) from accessing certain parts of the site.',
    ],

    'import' => [
        'title' => 'Import Roles',
        'permissions_hint' => 'Expected format: ["users.create", "users.delete"]',
    ],

    'index' => [
        'title' => 'Roles',
    ],

    'create' => [
        'title' => 'Create Role',
        'role_info_title' =>  'Role Information',
        'role_info_subtitle' => 'Give the role a name and describe what it is for.',
        'permissions_title' => 'Role Permissions',
        'permissions_subtitle' => 'Define what the role is allowed to do on the site.',
    ],

    'edit' => [
        'title' => 'Edit Role',
        'role_info_subtitle' => 'Describe what the role is for.',
    ],

    'labels' => [
        'name' => 'Name',
        'description' => 'Description',
        'permissions' => 'Permissions',
        'search_placeholder' => 'Search in Name, Description',

        'form' => [
            'name' => 'Name',
            'name_placeholder' => 'User',
            'description' => 'Description',
            'description_placeholder' => 'Describe what the role is for.',
        ],
    ],

    'singular' => 'role',
];
