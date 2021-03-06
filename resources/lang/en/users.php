<?php
return [
    "actions" => ["activate" => "Activate", "deactivate" => "Deactivate"],
    "alerts" => [
        "activated" => ":name was activated!",
        "bulk_activated" => ":count user was activated.|:count users were activated.",
        "bulk_deactivated" => ":count user was deactivated.|:count users were deactivated.",
        "bulk_deleted" => ":count user was deleted.|:count users were deleted.",
        "deactivated" => ":name was deactivated!",
        "deleted" => ":name was deleted!"
    ],
    "confirm_bulk_delete" => [
        "text" => "This will permanently delete the selected user(s). Please be sure you want to do this.",
        "title" => "Delete Selected Users"
    ],
    "confirm_delete" => [
        "text" => "This will permanently delete <strong>:name</strong>! You will not be able to recover any data associated with this user.",
        "title" => "Delete User"
    ],
    "deactivated" => [
        "message" => "Your account has been deactivated. If you believe this is a mistake, please contact our support to resolve this issue.",
        "title" => "Account Deactivated"
    ],
    "impersonate" => [
        "button" => "Impersonate",
        "leave" => "Leave impersonation",
        "notice" => "You are impersonating :name."
    ],
    "index" => ["title" => "Users"],
    "labels" => [
        "email" => "Email",
        "is_active" => "Status",
        "name" => "Name",
        "status_active" => "Active",
        "status_inactive" => "Inactive",
        "timezone" => "Timezone"
    ],
    "profile" => [
        "two_factor_sub_title" => "Add additional security to your account using multi-factor authentication.",
        "two_factor_title" => "Multi-Factor Authentication (MFA)"
    ],
    "user_management" => "User Management"
];
