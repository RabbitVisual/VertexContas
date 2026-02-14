@php
    $inspectionId = session('impersonate_inspection_id');
    $inspection = $inspectionId ? \Modules\Core\Models\Inspection::find($inspectionId) : null;
@endphp

@if($inspection && $inspection->status === 'active')
    <div class="fixed top-0 left-0 right-0 z-[100] bg-gradient-to-r from-amber-600 via-red-600 to-amber-600 animate-gradient-x shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 py-2 flex items-center justify-between">
            <div class="flex items-center gap-3 text-white">
                <div class="bg-white/20 p-1.5 rounded-lg animate-pulse">
                    <x-icon name="magnifying-glass-chart" style="solid" class="text-sm" />
                </div>
                <div class="flex flex-col leading-tight">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Segurança Ativa</span>
                    <span class="text-xs font-black uppercase tracking-widest">MODO DE INSPEÇÃO ATIVO - Suporte Vertex Solutions LTDA</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                @if(session()->has('original_agent_id') || auth()->user()->hasRole('suporte') || auth()->user()->hasRole('admin'))
                    <form action="{{ route('support.inspection.stop', $inspection) }}" method="POST">
                        @csrf
                        <button type="submit" class="group px-4 py-1.5 bg-white text-red-600 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-2 hover:bg-red-50 transition-all shadow-xl hover:scale-105 active:scale-95">
                            <x-icon name="door-open" style="solid" class="group-hover:translate-x-0.5 transition-transform" />
                            Encerrar Inspeção
                        </button>
                    </form>
                @else
                    <div class="hidden md:flex flex-col items-end leading-tight text-white/90">
                        <span class="text-[9px] font-black uppercase tracking-widest">Acesso pelo Agente</span>
                        <span class="text-[11px] font-bold">{{ $inspection->agent->name }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        @keyframes gradient-x {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .animate-gradient-x {
            background-size: 200% 200%;
            animation: gradient-x 3s ease infinite;
        }
        /* Offset for main content when inspection banner is active (navbar pt-20 + banner ~48px) */
        #main-content { padding-top: 8rem !important; }
    </style>

    <script>
        // Check session status every 5 seconds to detect if the agent ended the session
        // This ensures the client knows the inspection ended without a refresh
        (function() {
            let sessionCheckInterval = setInterval(async function() {
                try {
                    const response = await fetch('{{ route('support.inspection.check') }}');
                    const data = await response.json();

                    if (data && data.active === false) {
                        clearInterval(sessionCheckInterval);
                        window.location.reload();
                    }
                } catch (error) {
                    console.error('Erro ao verificar sessão de inspeção:', error);
                }
            }, 5000);
        })();
    </script>
@endif
