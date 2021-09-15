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
    | These component references are mostly for convenience and can also be
    | referenced (as well as any components not listed here) by using
    | <x-laravel-base::component-name> syntax.
    |
    | Note: Any components listed here that have an array for the config
    | value shouldn't be renamed or removed from the config since the
    | underlying component will reference the config values in the
    | component's array.
    |
    */
    'components' => [

        'html' => Components\Layouts\Html::class,

        'app' => Components\Layouts\App::class,

        'notification' => Components\Alerts\Notification::class,

        'alert' => Components\Alerts\Alert::class,

        'action-message' => 'laravel-base::components.alerts.action-message',

        'session-alert' => Components\Alerts\SessionAlert::class,

        'scroll-to-top-button' => 'laravel-base::components.button.scroll-to-top-button',

        'table' => Components\Table\Table::class,

        'tr' => Components\Table\Tr::class,

        'th' => [
            'class' => Components\Table\Th::class,

            /*
             * You may customize the classes added to a <th> tag by default here instead of
             * overriding the view yourself. We've included a sensible default.
             */
            'default_class' => 'relative overflow-hidden border-blue-gray-200 bg-blue-gray-50 text-left text-blue-gray-500 text-xs leading-4 font-medium uppercase focus:outline-none tracking-wider px-6 py-3',
        ],

        'td' => Components\Table\Td::class,

        'button' => Components\Button\Button::class,

        'link' => Components\Navigation\Link::class,

        'dropdown' => Components\Navigation\Dropdown::class,

        'dropdown-item' => Components\Navigation\DropdownItem::class,

        'dropdown-divider' => 'laravel-base::components.navigation.dropdown-divider',

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
