<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Erro' }} - {{ config('app.name') }}</title>
    <link rel="icon" href="{{ branding_favicon_url() }}">
    @vite(['resources/css/app.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --vertex-primary: #11c76f;
            --vertex-primary-glow: 0 0 20px rgba(17, 199, 111, 0.5), 0 0 40px rgba(17, 199, 111, 0.3);
        }
        .font-audiowide { font-family: 'Audiowide', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-white flex flex-col items-center justify-center overflow-hidden font-['Poppins',sans-serif]">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-slate-950 via-slate-900/95 to-slate-950 pointer-events-none" aria-hidden="true"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_50%_0%,rgba(17,199,111,0.08)_0%,transparent_60%)] pointer-events-none" aria-hidden="true"></div>

    <div class="relative z-10 flex flex-col items-center justify-center px-6 text-center max-w-2xl mx-auto">
        {{-- Logo --}}
        <a href="{{ url('/') }}" class="mb-10 focus:outline-none focus:ring-2 focus:ring-[#11c76f] focus:ring-offset-2 focus:ring-offset-slate-950 rounded-lg">
            <img src="{{ branding_logo_url('default', true) }}" alt="{{ config('app.name') }}" class="h-12 w-auto hover:opacity-90 transition-opacity" loading="eager">
        </a>

        {{-- Content Slot --}}
        @yield('content')

        {{-- Actions --}}
        <div class="flex flex-wrap gap-4 mt-10 justify-center">
            <button type="button" onclick="if (window.history.length > 1) { history.back(); } else { window.location.href = '{{ url('/') }}'; }" class="px-6 py-3 rounded-xl bg-[#11c76f] hover:bg-[#0fa85c] text-slate-950 font-semibold shadow-[0_0_20px_rgba(17,199,111,0.4)] hover:shadow-[0_0_30px_rgba(17,199,111,0.5)] transition-all duration-300">
                <i class="fa-pro fa-solid fa-arrow-left mr-2"></i>Voltar
            </button>
            <a href="{{ route('homepage') }}" class="px-6 py-3 rounded-xl border border-slate-600 hover:border-[#11c76f] hover:text-[#11c76f] font-semibold transition-all duration-300">
                <i class="fa-pro fa-solid fa-home mr-2"></i>Ir para Início
            </a>
        </div>
    </div>
</body>
</html>
