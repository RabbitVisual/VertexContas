<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
      :class="{ 'dark': darkMode }"
      x-init="$watch('darkMode', val => { localStorage.setItem('color-theme', val ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', val); })">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>(function(){var d=localStorage.getItem('color-theme')==='dark'||(!('color-theme' in localStorage)&&window.matchMedia('(prefers-color-scheme: dark)').matches);document.documentElement.classList.toggle('dark',d);})();</script>
    <title>{{ $title ?? 'Aceitar Termos - ' . config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ branding_favicon_url() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-900 font-sans text-gray-900 dark:text-gray-100 antialiased flex flex-col items-center justify-center p-4">
    @if(session('success'))
        <div class="w-full max-w-2xl mb-4 px-4 py-3 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div class="w-full max-w-2xl mb-4 px-4 py-3 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded-xl text-sm font-medium">
            {{ session('info') }}
        </div>
    @endif
    @yield('content')
</body>
</html>
