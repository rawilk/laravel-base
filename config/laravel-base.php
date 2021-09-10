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

];
