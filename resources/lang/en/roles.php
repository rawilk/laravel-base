<?php
return [
    "alerts" => [
        "bulk_deleted" => ":count role was deleted.|:count roles were deleted.",
        "created" => "Role was created successfully.",
        "deleted" => ":name was deleted!",
        "import_success" => "Imported :count role.|Imported :count roles.",
        "no_results" => "No roles found...",
        "permissions_cannot_be_modified" => "The permissions of the \":name\" role cannot be modified."
    ],
    "confirm_bulk_delete" => [
        "text" => "This will permanently delete the selected role(s). Please be sure you want to do this. This could affect any users assigned to the role(s) from accessing certain parts of the site.",
        "title" => "Delete Selected Roles"
    ],
    "confirm_delete" => [
        "text" => "This will permanently delete the <strong>:role</strong> role! Please be sure you want to do this. This could affect any users assigned to this role from accessing certain parts of the site.",
        "title" => "Delete Role"
    ],
    "create" => [
        "permissions_subtitle" => "Define what the role is allowed to do on the site.",
        "permissions_title" => "Role Permissions",
        "role_info_subtitle" => "Give the role a name and describe what it is for.",
        "role_info_title" => "Role Information",
        "title" => "Create Role"
    ],
    "edit" => ["role_info_subtitle" => "Describe what the role is for.", "title" => "Edit Role"],
    "import" => [
        "permissions_hint" => "Expected format: [\"users.create\", \"users.delete\"]",
        "title" => "Import Roles"
    ],
    "index" => ["title" => "Roles"],
    "labels" => [
        "description" => "Description",
        "form" => [
            "description" => "Description",
            "description_placeholder" => "Describe what the role is for.",
            "name" => "Name",
            "name_placeholder" => "User"
        ],
        "name" => "Name",
        "permissions" => "Permissions",
        "search_placeholder" => "Search in Name, Description"
    ],
    "singular" => "role"
];
