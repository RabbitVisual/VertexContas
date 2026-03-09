<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="{{ config('pwa.theme_color', '#11C76F') }}">

    <title>{{ $title ?? (config('pwa.short_name', 'Vertex') . ' — PWA') }}</title>
    <meta name="description" content="{{ $description ?? 'Vertex Contas: aplicativo progressivo para gestão financeira.' }}">
    <meta name="keywords" content="{{ $keywords ?? 'finanças, orçamento, metas, PWA' }}">
    <meta name="author" content="{{ $author ?? config('app.name') }}">

    <link rel="icon" type="image/svg+xml" href="{{ branding_favicon_url() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-gray-50 dark:bg-gray-900 font-sans text-gray-900 dark:text-gray-100 antialiased" style="padding-top: env(safe-area-inset-top); padding-left: env(safe-area-inset-left); padding-right: env(safe-area-inset-right); padding-bottom: env(safe-area-inset-bottom);">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
        {{ $slot }}
    </div>
</body>
</html>
