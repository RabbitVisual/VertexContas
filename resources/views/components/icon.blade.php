@props([
    'name'     => null,
    'style'    => null, // duotone, solid, regular, light, thin, brands
    'class'    => '',
    'bordered' => false,
    'pulled'   => null, // 'left' or 'right'
    'size'     => null, // 'xs', 'sm', 'lg', 'xl', '2xl', etc.
    'module'   => null, // 'homepage', 'paneladmin', etc.
])

@php
    // 1. Resolve Icon Name
    $iconName = $name;

    // Check if the provided name is a map alias
    if ($iconName && config("icon.map.{$iconName}")) {
        $iconName = config("icon.map.{$iconName}");
    }

    // Use module default if name is not provided
    if (!$iconName && $module) {
        $iconName = config("icon.defaults.{$module}");
    }

    // Final fallback
    $iconName = $iconName ?? 'circle-question';

    // 2. Resolve Style
    $styleKey = $style ?? config('icon.style', 'duotone');

    $styleMap = [
        'duotone' => 'fa-duotone',
        'solid'   => 'fa-solid',
        'regular' => 'fa-regular',
        'light'   => 'fa-light',
        'thin'    => 'fa-thin',
        'brands'  => 'fa-brands',
    ];

    $faStyle = $styleMap[$styleKey] ?? $styleMap['duotone'];

    // 3. Assemble Classes
    $classes = [
        $faStyle,
        "fa-{$iconName}",
        $bordered ? 'fa-border' : '',
        $pulled ? "fa-pull-{$pulled}" : '',
        $size ? "fa-{$size}" : '',
        $class
    ];

    $finalClass = implode(' ', array_filter($classes));
@endphp

<i {{ $attributes->merge(['class' => $finalClass]) }} aria-hidden="true"></i>
