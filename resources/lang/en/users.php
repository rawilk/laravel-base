<?php

return [
    'profile' => [
        // Two factor...
        'two_factor_title' => 'Two Factor Authentication',
        'two_factor_sub_title' => 'Add additional security to your account using two factor authentication.',
        'two_factor_recovery_codes_description' => 'Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.',
        'two_factor_setup_instructions' => "Two factor authentication is now enabled. Scan the following QR code using your phone's authenticator application.",
        'two_factor_setup_help' => 'If you are not able to scan the QR code, you can manually enter your secret key.',
        'two_factor_enabled_status' => 'You have enabled two factor authentication.',
        'two_factor_disabled_status' => 'You have not enabled two factor authentication.',
        'two_factor_description' => "When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.",
        'two_factor_triggers' => [
            'enable' => 'Enable',
            'regenerate_recovery_codes' => 'Regenerate Recovery Codes',
            'show_recovery_codes' => 'Show Recovery Codes',
            'disable' => 'Disable',
        ],
    ],

    // Two factor challenge...
    'two_factor' => [
        'title' => 'Two Factor Challenge',
        'app_code_description' => 'Please confirm access to your account by entering the authentication code provided by your authenticator application.',
        'recovery_code_description' => 'Please confirm access to your account by entering one of your emergency recovery codes.',
        'use_recovery_code_button' => 'Use a recovery code',
        'use_auth_code_button' => 'Use an authentication code',
        'verify_button' => 'Verify code',
        'invalid_code' => 'The provided two factor authentication code was invalid.',
    ],

    // User management menu title...
    'user_management' => 'User Management',

    // Main users page...
    'index' => [
        'title' => 'Users',
    ],

    // Labels...
    'labels' => [
        'name' => 'Name',
        'email' => 'Email',
        'timezone' => 'Timezone',
        'is_active' => 'Status',
        'status_active' => 'Active',
        'status_inactive' => 'Inactive',
    ],

    // Impersonation...
    'impersonate' => [
        'leave' => 'Leave impersonation',
        'notice' => 'You are impersonating :name.',
        'button' => 'Impersonate',
    ],

    // Alerts...
    'alerts' => [
        'activated' => ':name was activated!',
        'bulk_activated' => ':count user was activated.|:count users were activated.',
        'bulk_deactivated' => ':count user was deactivated.|:count users were deactivated.',
        'bulk_deleted' => ':count user was deleted.|:count users were deleted.',
        'deactivated' => ':name was deactivated!',
        'deleted' => ':name was deleted!',
    ],

    // Confirm Delete...
    'confirm_delete' => [
        'title' => 'Delete User',
        'text' => 'This will permanently delete <strong>:name</strong>! You will not be able to recover any data associated with this user.',
    ],

    // Confirm Bulk Delete...
    'confirm_bulk_delete' => [
        'title' => 'Delete Selected Users',
        'text' => 'This will permanently delete the selected user(s). Please be sure you want to do this.',
    ],

    // Actions...
    'actions' => [
        'activate' => 'Activate',
        'deactivate' => 'Deactivate',
    ],

    // Deactivation Notice...
    'deactivated' => [
        'title' => 'Account Deactivated',
        'message' => 'Your account has been deactivated. If you believe this is a mistake, please contact our support to resolve this issue.',
    ],
];
