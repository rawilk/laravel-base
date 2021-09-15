<?php

use Rawilk\LaravelBase\Components;

return [
    /*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
    |
    | Below is a reference of all the blade components that should be loaded
    | into your app by this package. You can disable or overwrite any
    | component class or alias you want.
    |
    */
    'components' => [

        'html' => [
            'class' => Components\Layouts\Html::class,
            'view' => 'laravel-base::components.layouts.html',
        ],

        'app' => [
            'class' => Components\Layouts\App::class,
            'view' => 'laravel-base::components.layouts.app',
        ],

        'notification' => [
            'class' => Components\Alerts\Notification::class,
            'view' => 'laravel-base::components.alerts.notification',
        ],

        'alert' => [
            'class' => Components\Alerts\Alert::class,
            'view' => 'laravel-base::components.alerts.alert',
        ],

        'action-message' => [
            'class' => 'laravel-base::components.alerts.action-message',
        ],

        'session-alert' => [
            'class' => Components\Alerts\SessionAlert::class,
            'view' => 'laravel-base::components.alerts.session-alert',
        ],

        'scroll-to-top-button' => [
            'class' => 'laravel-base::components.button.scroll-to-top-button',
        ],

        'table' => [
            'class' => Components\Table\Table::class,
            'view' => 'laravel-base::components.table.table',
        ],

        'tr' => [
            'class' => Components\Table\Tr::class,
            'view' => 'laravel-base::components.table.tr',
        ],

        'th' => [
            'class' => Components\Table\Th::class,
            'view' => 'laravel-base::components.table.th',

            /*
             * You may customize the classes added to a <th> tag by default here instead of
             * overriding the view yourself. We've included a sensible default.
             */
            'default_class' => 'relative overflow-hidden border-blue-gray-200 bg-blue-gray-50 text-left text-blue-gray-500 text-xs leading-4 font-medium uppercase focus:outline-none tracking-wider px-6 py-3',
        ],

        'td' => [
            'class' => Components\Table\Td::class,
            'view' => 'laravel-base::components.table.td',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Component Prefix
    |--------------------------------------------------------------------------
    |
    | This value will a prefix for all laravel-base blade components. By
    | default it's empty. This is useful if you want to avoid collision
    | with components from other libraries.
    |
    | If set with "tw", for example, you can reference components like this:
    |
    | <x-tw-html />
    |
    */
    'component_prefix' => '',

    /*
    |--------------------------------------------------------------------------
    | LaravelBase Assets URL
    |--------------------------------------------------------------------------
    |
    | This value sets the path to the LaravelBase JavaScript assets, for cases
    | where your app's domain root is not the correct path. By default,
    | LaravelBase will load its JavaScript assets from the app's
    | "relative root".
    |
    | Examples: "/assets", "myapp.com/app",
    |
    */
    'asset_url' => null,

];
