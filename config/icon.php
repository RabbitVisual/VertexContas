<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Icons by Module
    |--------------------------------------------------------------------------
    |
    | Here you can define the default icon name for each module.
    | Usage in Blade: <x-icon module="homepage" />
    |
    */
    'defaults' => [
        'homepage' => 'home',
        'paneladmin' => 'shield-halved',
        'panelsuporte' => 'headset',
        'paneluser' => 'user',
        'core' => 'wallet',
        'blog' => 'newspaper',
        'gateways' => 'credit-card',
    ],

    /*
    |--------------------------------------------------------------------------
    | Global Icon Styles
    |--------------------------------------------------------------------------
    |
    | Default Font Awesome Pro style for the <x-icon> component.
    | Options: duotone, solid, regular, light, thin, brands
    |
    */
    'style' => 'duotone',

    /*
    |--------------------------------------------------------------------------
    | Custom Icon Mappings
    |--------------------------------------------------------------------------
    |
    | Useful for aliasing complex icon names or centralizing themes.
    |
    */
    'map' => [
        'terms' => 'scroll',
        'privacy' => 'shield-halved',
        'contact' => 'envelope',
    ],
];
