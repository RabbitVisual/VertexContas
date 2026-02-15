@php
    $inspectionId = session('impersonate_inspection_id');
    $inspection = $inspectionId ? \Modules\Core\Models\Inspection::find($inspectionId) : null;
    $isProInspection = $inspection && \Modules\Core\Services\InspectionGuard::isProClient();
    $isAgentView = session()->has('original_agent_id') || auth()->user()->hasRole('suporte') || auth()->user()->hasRole('admin');
    $startedAt = $inspection?->started_at;
    $duration = $startedAt ? $startedAt->diffForHumans(null, true) : null;
@endphp

@if($inspection && $inspection->status === 'active')
    <div class="fixed top-0 left-0 right-0 z-[100] {{ $isProInspection ? 'bg-gradient-to-r from-amber-500 via-amber-600 to-amber-500' : 'bg-gradient-to-r from-amber-600 via-red-600 to-amber-600' }} animate-gradient-x shadow-2xl">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 py-2 flex flex-wrap items-center justify-between gap-2 sm:gap-0">
            <div class="flex items-center gap-2 sm:gap-3 text-white min-w-0">
                <div class="bg-white/20 p-1.5 rounded-lg animate-pulse flex-shrink-0">
                    <x-icon name="magnifying-glass-chart" style="solid" class="text-xs sm:text-sm" />
                </div>
                <div class="flex flex-col leading-tight min-w-0">
                    <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-[0.15em] sm:tracking-[0.2em] opacity-80">Segurança Ativa</span>
                    <span class="text-[10px] sm:text-xs font-black uppercase tracking-widest truncate">
                        MODO DE INSPEÇÃO ATIVO - Suporte Vertex Solutions LTDA
                        @if($isProInspection)
                            <span class="ml-1 sm:ml-2 px-2 py-0.5 rounded-md bg-white/25 text-[9px] sm:text-[10px] font-black uppercase flex-shrink-0">VIP</span>
                        @endif
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">
                @if($isProInspection && $duration)
                    <div class="hidden sm:flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/15 text-[9px] font-bold uppercase tracking-wider">
                        <x-icon name="clock" style="solid" class="w-3 h-3" />
                        <span>{{ $duration }}</span>
                    </div>
                @endif
                @if($isAgentView)
                    <form action="{{ route('support.inspection.stop', $inspection) }}" method="POST" class="flex-shrink-0">
                        @csrf
                        <button type="submit" class="group px-3 sm:px-4 py-1.5 bg-white text-red-600 rounded-full text-[9px] sm:text-[10px] font-black uppercase tracking-widest flex items-center gap-2 hover:bg-red-50 transition-all shadow-xl hover:scale-105 active:scale-95">
                            <x-icon name="door-open" style="solid" class="group-hover:translate-x-0.5 transition-transform w-3 h-3 sm:w-3.5 sm:h-3.5" />
                            Encerrar Inspeção
                        </button>
                    </form>
                @else
                    <div class="flex flex-col items-end leading-tight text-white/90">
                        <span class="text-[8px] sm:text-[9px] font-black uppercase tracking-widest">Agente</span>
                        <span class="text-[10px] sm:text-[11px] font-bold truncate max-w-[120px] sm:max-w-none">{{ $inspection->agent->name }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- PRO: Mini painel flutuante com status e dicas --}}
    @if($isProInspection && !$isAgentView)
        <div x-data="{ minimizado: false }" class="fixed bottom-4 right-4 z-[90] sm:bottom-6 sm:right-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border-2 border-amber-500/30 dark:border-amber-500/20 overflow-hidden transition-all duration-300 flex flex-col" :class="minimizado ? 'w-14 h-14' : 'w-64 sm:w-72'">
                <div x-show="!minimizado" x-transition class="flex-1">
                    <div class="p-4 pr-10">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                                <x-icon name="crown" style="solid" class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">Atendimento VIP</p>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400">Status em tempo real</p>
                            </div>
                        </div>
                        <ul class="space-y-2 text-[11px] text-gray-600 dark:text-gray-400">
                            <li class="flex items-center gap-2">
                                <x-icon name="check-circle" style="solid" class="w-4 h-4 text-emerald-500 flex-shrink-0" />
                                <span>Sessão verificada a cada 2s</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <x-icon name="shield-halved" style="solid" class="w-4 h-4 text-blue-500 flex-shrink-0" />
                                <span>Ações sensíveis bloqueadas</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <x-icon name="clock" style="solid" class="w-4 h-4 text-amber-500 flex-shrink-0" />
                                <span>Recarrega ao encerrar</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <button type="button" @click="minimizado = !minimizado" class="absolute top-2 right-2 p-1.5 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-500/20 text-amber-600 dark:text-amber-400 transition-colors z-10" :title="minimizado ? 'Expandir' : 'Minimizar'">
                    <span class="inline-block transition-transform duration-200" :class="minimizado ? 'rotate-180' : ''">
                        <x-icon name="chevron-down" style="solid" class="w-4 h-4" />
                    </span>
                </button>
                <div x-show="minimizado" x-transition class="absolute inset-0 flex items-center justify-center cursor-pointer" @click="minimizado = false" title="Clique para expandir">
                    <x-icon name="crown" style="solid" class="w-6 h-6 text-amber-500" />
                </div>
            </div>
        </div>
    @endif

    <style>
        @keyframes gradient-x {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .animate-gradient-x {
            background-size: 200% 200%;
            animation: gradient-x 3s ease infinite;
        }
        #main-content { padding-top: 8rem !important; }
    </style>

    <script>
        (function() {
            var isPro = {{ $isProInspection ? 'true' : 'false' }};
            var intervalMs = isPro ? 2000 : 5000;

            let sessionCheckInterval = setInterval(async function() {
                try {
                    const response = await fetch('{{ route('support.inspection.check') }}', {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await response.json();

                    if (data && data.active === false) {
                        clearInterval(sessionCheckInterval);
                        window.location.reload();
                    }
                } catch (error) {
                    console.error('Erro ao verificar sessão de inspeção:', error);
                }
            }, intervalMs);
        })();
    </script>
@endif
