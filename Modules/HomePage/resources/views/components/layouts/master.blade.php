@props([
    'title' => 'Vertex Contas - Domine sua Liberdade Financeira com a Regra 50/30/20',
    'metaDescription' => 'Gerencie suas finanças com inteligência. O único sistema com mentor virtual, análise 50/30/20 e consultoria mensal automática em PDF. Comece grátis!',
    'metaKeywords' => 'gestão financeira, regra 50/30/20, finanças pessoais, planejamento financeiro, vertex contas, saas financeiro',
    'ogImage' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
          darkMode: localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
      }"
      :class="{ 'dark': darkMode }"
      x-init="$watch('darkMode', val => {
          localStorage.setItem('color-theme', val ? 'dark' : 'light');
          document.documentElement.classList.toggle('dark', val);
      })"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- SEO Meta Tags --}}
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $metaKeywords }}">
    <meta name="robots" content="index, follow">

    {{-- Open Graph (Facebook, LinkedIn, etc.) --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('images/og-image.svg') }}">
    <meta property="og:site_name" content="Vertex Contas">
    <meta property="og:locale" content="pt_BR">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('images/og-image.svg') }}">

    {{-- Favicon & Apple Touch Icon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('storage/logos/favicon.svg') }}">
    <link rel="icon" type="image/svg+xml" sizes="32x32" href="{{ asset('storage/logos/favicon-32x32.svg') }}">
    <link rel="icon" type="image/svg+xml" sizes="16x16" href="{{ asset('storage/logos/favicon-16x16.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/logos/apple-touch-icon.svg') }}">

    {{-- Anti-FOUC: Flowbite/Tailwind dark mode + modo privacidade (sensitive) --}}
    <script>
        (function(){
            var d=localStorage.getItem('color-theme')==='dark'||(!('color-theme' in localStorage)&&window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark',d);
            var isHidden=localStorage.getItem('sensitive-hidden')==='true';
            if(document.body){document.body.classList.toggle('sensitive-hidden',isHidden);}
            else{window.addEventListener('DOMContentLoaded',function(){document.body.classList.toggle('sensitive-hidden',isHidden);});}
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(request()->routeIs('homepage'))
        @vite('resources/js/charts-apex.js')
    @endif

    {{-- Module Specific Assets --}}
    @stack('styles')
</head>
<body class="font-sans text-gray-900 dark:text-gray-100 bg-background dark:bg-background antialiased">
    <x-loading-overlay />

    <!-- Main Content -->
    {{ $slot }}

    {{-- Scripts --}}
    @stack('scripts')
</body>
</html>
