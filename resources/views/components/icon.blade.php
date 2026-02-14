@props([
    'name'     => null,
    'style'    => null, // duotone, duotone-regular, duotone-light, duotone-thin, solid, regular, light, thin, sharp-solid, sharp-regular, sharp-light, sharp-thin, sharp-duotone, sharp-duotone-regular, sharp-duotone-light, sharp-duotone-thin, brands
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

    // 2. Resolve Style - Font Awesome Pro 7.1 webfonts
    $styleKey = $style ?? config('icon.style', 'duotone');

    // Single-class styles (fa-solid, fa-duotone, etc.)
    $singleClassStyles = [
        'solid'   => 'fa-solid',
        'regular' => 'fa-regular',
        'light'   => 'fa-light',
        'thin'    => 'fa-thin',
        'brands'  => 'fa-brands',
    ];

    // Multi-class styles (fa-duotone fa-regular, fa-sharp fa-solid, etc.)
    $multiClassStyles = [
        'duotone'              => ['fa-duotone'],
        'duotone-regular'      => ['fa-duotone', 'fa-regular'],
        'duotone-light'        => ['fa-duotone', 'fa-light'],
        'duotone-thin'         => ['fa-duotone', 'fa-thin'],
        'sharp-solid'          => ['fa-sharp', 'fa-solid'],
        'sharp-regular'        => ['fa-sharp', 'fa-regular'],
        'sharp-light'          => ['fa-sharp', 'fa-light'],
        'sharp-thin'           => ['fa-sharp', 'fa-thin'],
        'sharp-duotone'        => ['fa-sharp-duotone', 'fa-solid'],
        'sharp-duotone-regular'=> ['fa-sharp-duotone', 'fa-regular'],
        'sharp-duotone-light'  => ['fa-sharp-duotone', 'fa-light'],
        'sharp-duotone-thin'   => ['fa-sharp-duotone', 'fa-thin'],
    ];

    $faStyle = $singleClassStyles[$styleKey] ?? null;
    $faClasses = $faStyle ? [$faStyle] : ($multiClassStyles[$styleKey] ?? ['fa-duotone']);

    // 3. Assemble Classes
    $classes = array_merge(
        $faClasses,
        ["fa-{$iconName}"],
        $bordered ? ['fa-border'] : [],
        $pulled ? ["fa-pull-{$pulled}"] : [],
        $size ? ["fa-{$size}"] : [],
        $class ? [trim($class)] : []
    );

    $finalClass = implode(' ', array_filter($classes));
@endphp

<i {{ $attributes->merge(['class' => $finalClass]) }} aria-hidden="true"></i>
