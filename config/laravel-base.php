<?php

use Rawilk\LaravelBase\Components;
use Rawilk\LaravelBase\Features;

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

        // Alerts
        'action-message' => 'laravel-base::components.alerts.action-message',
        'alert' => Components\Alerts\Alert::class,
        'notification' => Components\Alerts\Notification::class,
        'session-alert' => Components\Alerts\SessionAlert::class,

        // Auth
        'confirms-password' => Components\Auth\ConfirmsPassword::class,

        // Button
        'button' => Components\Button\Button::class,
        'scroll-to-top-button' => 'laravel-base::components.button.scroll-to-top-button',

        // DateTime
        'countdown' => Components\DateTime\Countdown::class,

        // Elements
        'badge' => Components\Elements\Badge::class,
        'card' => Components\Elements\Card::class,
        'filter-breadcrumbs' => 'laravel-base::components.elements.filter-breadcrumbs',
        'tooltip' => Components\Elements\Tooltip::class,
        'tooltip-help' => Components\Elements\TooltipHelp::class,
        'topbar' => Components\Elements\Topbar::class,

        // Feeds
        'feed' => Components\Feeds\Feed::class,
        'feed-item' => Components\Feeds\FeedItem::class,

        // Layouts
        'app' => Components\Layouts\App::class,
        'html' => Components\Layouts\Html::class,

        // Lists
        'action-item' => Components\Lists\ActionItem::class,
        'action-item-list' => 'laravel-base::components.lists.action-item-list',
        'description-list' => 'laravel-base::components.lists.description-list',
        'description-list-item' => Components\Lists\DescriptionListItem::class,
        'info-list' => 'laravel-base::components.lists.info-list',
        'info-list-item' => Components\Lists\InfoListItem::class,

        // Misc
        'copy-to-clipboard' => Components\Misc\CopyToClipboard::class,

        // Modal
        'advanced-filters' => Components\Modal\AdvancedFilters::class,
        'dialog-modal' => Components\Modal\DialogModal::class,
        'modal' => Components\Modal\Modal::class,
        'import-modal' => Components\Modal\ImportModal::class,
        'slide-over' => Components\Modal\SlideOver::class,
        'slide-over-form' => Components\Modal\SlideOverForm::class,

        // Navigation
        'action-menu' => Components\Navigation\ActionMenu::class,
        'dropdown' => Components\Navigation\Dropdown::class,
        'dropdown-item' => Components\Navigation\DropdownItem::class,
        'dropdown-divider' => 'laravel-base::components.navigation.dropdown-divider',
        'inner-nav' => [
            'class' => Components\Navigation\InnerNav::class,

            /*
             * This will be the default space from the top the nav links will be
             * when stickyNav is enabled.
             */
            'default_sticky_offset' => 'md:top-2',
        ],
        'inner-nav-item' => Components\Navigation\InnerNavItem::class,
        'link' => Components\Navigation\Link::class,

        // Table
        'table' => Components\Table\Table::class,
        'tr' => Components\Table\Tr::class,
        'th' => [
            'class' => Components\Table\Th::class,

            /*
             * You may customize the classes added to a <th> tag by default here instead of
             * overriding the view yourself. We've included a sensible default.
             */
            'default_class' => 'relative overflow-hidden border-slate-200 bg-slate-50 text-left text-slate-500 text-xs leading-4 font-medium uppercase focus:outline-none tracking-wider px-6 py-3',
        ],
        'td' => Components\Table\Td::class,
        'column-select' => Components\Table\ColumnSelect::class,

        // Tabs
        'tab' => Components\Tabs\Tab::class,
        'tabs' => Components\Tabs\Tabs::class,

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

    /*
    |--------------------------------------------------------------------------
    | LaravelBase Guard
    |--------------------------------------------------------------------------
    |
    | Here you may specify which authentication guard LaravelBase will use while
    | authenticating users. This value should correspond with one of your
    | guards that is already present in your "auth" configuration file.
    |
    */
    'guard' => 'web',

    /*
    |--------------------------------------------------------------------------
    | LaravelBase Routes Prefix / Subdomain
    |--------------------------------------------------------------------------
    |
    | Here you may specify which prefix LaravelBase will assign to all the routes
    | that it registers with the application. If necessary, you may change
    | subdomain under which all the LaravelBase routes will be available.
    |
    */
    'prefix' => '',

    'domain' => null,

    /*
    |--------------------------------------------------------------------------
    | LaravelBase Routes Middleware
    |--------------------------------------------------------------------------
    |
    | Here you may specify which middleware LaravelBase will assign to the routes
    | that it registers with the application. If necessary, you may change
    | these middleware but typically this provided default is preferred.
    |
    */
    'middleware' => ['web', \Rawilk\LaravelBase\Http\Middleware\EnsureActiveUserMiddleware::class],
    'admin_middleware' => ['web', 'auth', \Rawilk\LaravelBase\Http\Middleware\EnsureActiveUserMiddleware::class],

    /*
    |--------------------------------------------------------------------------
    | Username / Email
    |--------------------------------------------------------------------------
    |
    | This value defines which model attributes should be considered as your
    | application's "username" field. Typically, this might be the email
    | address of the users but you are free to change this value here.
    |
    | Out of the box, LaravelBase expects forgot password and reset password
    | requests to have a field named 'email'. If the application uses
    | another name for hte field you may define it below as needed.
    |
    */
    'username' => 'email',

    'email' => 'email',

    /*
    |--------------------------------------------------------------------------
    | LaravelBase Password Broker
    |--------------------------------------------------------------------------
    |
    | Here you may specify which password broker LaravelBase can use when a user
    | is resetting their password. This configured value should match one
    | of your password brokers setup in your "auth" configuration file.
    |
    */
    'passwords' => 'users',

    /*
    |--------------------------------------------------------------------------
    | Home Path
    |--------------------------------------------------------------------------
    |
    | Here you may configure the path where users will get redirected during
    | authentication or password reset when the operations are successful
    | and the user is authenticated. You are free to change this value.
    |
    | Leave value as "null" to default the value to the
    | RouteServiceProvider::HOME value.
    |
    */
    'home' => null,

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Some of the LaravelBase features are optional. You may disable the features
    | by removing them from this array. You're free to only remove some of
    | these features or you can remove all of these if you need to.
    |
    */
    'features' => [
        Features::registration(),
        // Features::emailVerification(),
        Features::resetPasswords(),
        Features::twoFactorAuthentication([
            'confirmPassword' => true, // Forces confirm password when enabling, disabling, etc.
        ]),
        Features::webauthn([
            'confirmPassword' => true, // Forces confirm password when enabling, disabling, etc.
        ]),

        // Profile features...
        Features::avatars(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        Features::accountDeletion(),

        // Admin features...
        Features::userManagement(),
        Features::roleManagement(),
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    |
    | Here you may define a route prefix and name prefix for any admin routes
    | defined in this package and/or your application. We've set a sensible
    | default for you.
    |
    */
    'admin_route_prefix' => 'admin',

    'admin_route_name_prefix' => 'admin.',

    /*
    |--------------------------------------------------------------------------
    | Admin View Layout
    |--------------------------------------------------------------------------
    |
    | Here you may customize the path to the layout view that should be used
    | to render any admin views from this package's routes. We've set a
    | default for you if you are using the stubs from this package.
    |
    */
    'admin_view_layout' => 'layouts.app.base',

    /*
    |--------------------------------------------------------------------------
    | Impersonation
    |--------------------------------------------------------------------------
    |
    */
    'impersonation' => [
        'session_key' => env('LARAVEL_BASE_IMPERSONATION_KEY', 'laravel_base.impersonated_by'),
        'remember_session_key' => env('LARAVEL_BASE_IMPERSONATION_REMEMBER_KEY', 'laravel_base.impersonated_remember'),
        'name_session_key' => env('LARAVEL_BASE_IMPERSONATION_NAME_KEY', 'laravel_base.impersonated_name'),
        'session_id_key' => env('LARAVEL_BASE_IMPERSONATION_SESSION_ID_KEY', 'laravel_base.impersonated_session_id'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Avatar Disk
    |--------------------------------------------------------------------------
    |
    | This configuration value determines the default disk that will be used
    | when storing profile photos for your application's users. We've set it
    | to a sensible default for a custom disk name, but this will require
    | you to create the disk before you can use this feature. You may set
    | it to "public" to avoid this.
    |
    */
    'avatar_disk' => 'avatars',

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    |
    | Here you may customize the Livewire components used for certain actions
    | such as logging in. The components provided in this package should
    | work in most cases, but sometimes you may need more control over
    | the component, so you may either extend or use a completely custom
    | class of your own.
    |
    */
    'livewire' => [
        'login' => \Rawilk\LaravelBase\Http\Livewire\Auth\Login::class,
        'register' => \Rawilk\LaravelBase\Http\Livewire\Auth\Register::class,
        'roles' => [
            'index' => \Rawilk\LaravelBase\Http\Livewire\Roles\Index::class,
            'create' => \Rawilk\LaravelBase\Http\Livewire\Roles\Create::class,
            'edit' => \Rawilk\LaravelBase\Http\Livewire\Roles\Edit::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Model Binding
    |--------------------------------------------------------------------------
    |
    | Here you may extend or define your own models to use instead of ours for
    | certain things. Be sure to implement the correct interfaces for each
    | of them though.
    |
    */
    'models' => [
        /*
         * Model used to track user initiated imports.
         * Interface: \Rawilk\LaravelBase\Contracts\Importable
         */
        'import' => \App\Models\Imports\Import::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Authenticator App
    |--------------------------------------------------------------------------
    |
    | Here you may customize the table name and model used for a user's stored
    | two-factor authenticator apps.
    |
    */
    'authenticator_apps' => [
        'table' => 'authenticator_apps',

        /*
         * You may extend our model, or use your own. If you use your
         * own model, it must implement the \Rawilk\LaravelBase\Contracts\Models\AuthenticatorApp
         * contract.
         */
        'model' => \Rawilk\LaravelBase\Models\AuthenticatorApp::class,

        /*
         * You may restrict the amount of authenticator apps a user may register to their account.
         * Set to `null` for unlimited registrations per user.
         */
        'max_per_user' => env('AUTHENTICATOR_APP_USER_MAX', 3),
    ],

    /*
    |--------------------------------------------------------------------------
    | WebAuthn
    |--------------------------------------------------------------------------
    |
    | If you wish to restrict users on how many WebAuthn security keys
    | they may register to their account, you may do so here. A `null` value
    | will not have any limits on the keys.
    |
    */
    'webauthn' => [
        'max_security_keys_per_user' => env('WEBAUTHN_MAX_SECURITY_KEYS', 5),

        'max_internal_keys_per_user' => env('WEBAUTHN_MAX_INTERNAL_KEYS'),
    ],

];
