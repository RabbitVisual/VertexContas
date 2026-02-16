@php
    $inspectionId = session('impersonate_inspection_id');
    $inspection = $inspectionId ? \Modules\Core\Models\Inspection::find($inspectionId) : null;
    $isProInspection = $inspection && \Modules\Core\Services\InspectionGuard::isProClient();
    $isAgentView = session()->has('original_agent_id') || auth()->user()->hasRole('suporte') || auth()->user()->hasRole('admin');
    $startedAt = $inspection?->started_at;
@endphp

@if($inspection && $inspection->status === 'active')
    {{-- Flowbite Floating Banner --}}
    <div id="inspection-banner" tabindex="-1" class="fixed top-0 start-0 z-50 w-full p-4 border-b border-slate-200 dark:border-slate-700 {{ $isProInspection ? 'bg-amber-500/90 dark:bg-amber-600/90' : 'bg-amber-600/90 dark:bg-red-600/90' }} backdrop-blur-xl shadow-lg">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2 sm:gap-0">
            <div class="flex items-center gap-2 sm:gap-3 text-white min-w-0">
                <div class="bg-white/20 p-1.5 rounded-lg animate-pulse flex-shrink-0">
                    <x-icon name="magnifying-glass-chart" style="solid" class="text-xs sm:text-sm" />
                </div>
                <div class="flex flex-col leading-tight min-w-0">
                    <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-[0.15em] sm:tracking-[0.2em] opacity-90">Segurança Ativa</span>
                    <span class="text-[10px] sm:text-xs font-black uppercase tracking-widest truncate">
                        MODO DE INSPEÇÃO ATIVO - Suporte {{ config('app.name') }}
                        @if($isProInspection)
                            <span class="ml-1 sm:ml-2 px-2 py-0.5 rounded-md bg-white/25 text-[9px] sm:text-[10px] font-black uppercase flex-shrink-0">VIP</span>
                        @endif
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">
                <div id="inspection-timer" class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/15 text-[9px] sm:text-[10px] font-bold uppercase tracking-wider"
                     data-started="{{ $startedAt?->toIso8601String() }}">
                    <x-icon name="clock" style="solid" class="w-3 h-3" />
                    <span>--:--:--</span>
                </div>
                @if($isAgentView)
                    <button type="button" data-modal-target="inspection-stop-modal" data-modal-toggle="inspection-stop-modal"
                            class="group px-3 sm:px-4 py-1.5 bg-white text-red-600 rounded-full text-[9px] sm:text-[10px] font-black uppercase tracking-widest flex items-center gap-2 hover:bg-red-50 transition-all shadow-xl hover:scale-105 active:scale-95">
                        <x-icon name="door-open" style="solid" class="group-hover:translate-x-0.5 transition-transform w-3 h-3 sm:w-3.5 sm:h-3.5" />
                        Encerrar Sessão e Relatar
                    </button>
                @else
                    <div class="flex flex-col items-end leading-tight text-white/90">
                        <span class="text-[8px] sm:text-[9px] font-black uppercase tracking-widest">Agente</span>
                        <span class="text-[10px] sm:text-[11px] font-bold truncate max-w-[120px] sm:max-w-none">{{ $inspection->agent->name }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Flowbite Modal: Mandatory Audit Report --}}
    @if($isAgentView)
    <div id="inspection-stop-modal" tabindex="-1" aria-hidden="true" data-modal-backdrop="static"
         class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[110] justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b border-slate-200 dark:border-slate-700 rounded-t">
                    <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <x-icon name="clipboard-list" style="duotone" class="text-amber-500 w-5 h-5" />
                        Encerrar Inspeção - Relatório Obrigatório
                    </h3>
                    <button type="button" data-modal-hide="inspection-stop-modal"
                            class="text-slate-400 bg-transparent hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-sm p-1.5 hover:text-slate-900 dark:hover:text-white">
                        <x-icon name="xmark" style="solid" class="w-5 h-5" />
                        <span class="sr-only">Fechar</span>
                    </button>
                </div>
                <form action="{{ route('support.inspection.stop', $inspection) }}" method="POST">
                    @csrf
                    <div class="p-4 md:p-5 space-y-4">
                        @if($errors->has('report'))
                            <div class="p-3 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-400 text-sm">
                                {{ $errors->first('report') }}
                            </div>
                        @endif
                        <div>
                            <label for="inspection-report" class="block mb-2 text-sm font-bold text-slate-900 dark:text-white">
                                Resumo da Ação Técnica <span class="text-red-500">*</span>
                            </label>
                            <textarea id="inspection-report" name="report" rows="5" required minlength="10"
                                      placeholder="Descreva as ações realizadas durante a inspeção, diagnóstico e próximos passos recomendados..."
                                      class="block w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary">{{ old('report') }}</textarea>
                            <p class="mt-1 text-xs text-slate-500">Mínimo 10 caracteres. Este relatório será registrado na auditoria.</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 p-4 md:p-5 border-t border-slate-200 dark:border-slate-700 rounded-b">
                        <button type="button" data-modal-hide="inspection-stop-modal"
                                class="px-4 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-widest rounded-xl transition-all flex items-center gap-2">
                            <x-icon name="door-open" style="solid" class="w-4 h-4" />
                            Encerrar e Relatar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

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
        #main-content { padding-top: 6rem !important; }
    </style>

    <script>
        (function() {
            const timerEl = document.getElementById('inspection-timer');
            const startedAt = timerEl?.dataset.started;
            if (timerEl && startedAt) {
                const start = new Date(startedAt).getTime();
                function update() {
                    const now = Date.now();
                    const diff = Math.floor((now - start) / 1000);
                    const h = Math.floor(diff / 3600);
                    const m = Math.floor((diff % 3600) / 60);
                    const s = diff % 60;
                    const span = timerEl.querySelector('span');
                    if (span) span.textContent = String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
                }
                update();
                setInterval(update, 1000);
            }

            const isPro = {{ $isProInspection ? 'true' : 'false' }};
            const intervalMs = isPro ? 2000 : 5000;
            let sessionCheckInterval = setInterval(async function() {
                try {
                    const response = await fetch('{{ route('support.inspection.check') }}', {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await response.json();
                    if (data && data.active === false) {
                        clearInterval(sessionCheckInterval);
                        window.location.href = data.redirect || window.location.href;
                    }
                } catch (error) {
                    console.error('Erro ao verificar sessão de inspeção:', error);
                }
            }, intervalMs);
        })();

            @if($isAgentView && $errors->has('report'))
            document.addEventListener('DOMContentLoaded', function() {
                const btn = document.querySelector('[data-modal-toggle="inspection-stop-modal"]');
                if (btn) btn.click();
            });
            @endif
    </script>
@endif
