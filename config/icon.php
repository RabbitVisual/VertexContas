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
    | Default Font Awesome Pro 7.1 style for the <x-icon> component.
    | Classic: solid, regular, light, thin
    | Duotone: duotone, duotone-regular, duotone-light, duotone-thin
    | Sharp: sharp-solid, sharp-regular, sharp-light, sharp-thin
    | Sharp Duotone: sharp-duotone, sharp-duotone-regular, sharp-duotone-light, sharp-duotone-thin
    | Brands: brands
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
        'gauge' => 'gauge-high',
        'arrow-right-left' => 'right-left',
    ],
];
