<x-homepage::layouts.master>
    <div class="min-h-screen flex flex-col items-center justify-center px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-900 to-slate-800 text-white relative overflow-hidden">

        <!-- Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] bg-primary/20 rounded-full blur-3xl opacity-30 animate-pulse"></div>
            <div class="absolute -bottom-[20%] -right-[10%] w-[50%] h-[50%] bg-blue-500/20 rounded-full blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="relative z-10 text-center max-w-2xl mx-auto">
            <div class="mb-8 transform scale-150">
                <x-logo variant="icon" class="h-24 w-auto mx-auto mb-6 drop-shadow-lg" />
            </div>

            <h1 class="text-4xl sm:text-6xl font-black mb-4 tracking-tight drop-shadow-md">
                Estamos em <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-blue-400">Manutenção</span>
            </h1>

            <p class="text-xl sm:text-2xl text-slate-300 mb-10 font-light leading-relaxed">
                Estamos fazendo atualizações importantes para melhorar sua experiência. Voltaremos em breve com novidades incríveis!
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <div class="flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-md rounded-full border border-white/20 shadow-lg">
                    <x-icon name="clock" class="w-5 h-5 text-primary" />
                    <span class="font-medium">Previsão de retorno: Em alguns minutos</span>
                </div>
            </div>

            <div class="mt-12 text-slate-400 text-sm">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
                @auth
                    @if(request()->user()->hasRole('admin'))
                        <div class="mt-4">
                            <a href="{{ route('admin.index') }}" class="text-primary hover:text-white transition-colors underline decoration-dotted">
                                Acessar Painel Admin (Bypass Ativo)
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</x-homepage::layouts.master>
