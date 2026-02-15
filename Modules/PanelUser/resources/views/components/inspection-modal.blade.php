@php
    $pendingInspection = \Modules\Core\Models\Inspection::where('user_id', auth()->id())
        ->where('status', 'pending')
        ->latest()
        ->first();
    $isPro = auth()->user()?->isPro() ?? false;
@endphp

@if($pendingInspection)
<div x-data="{
        show: true,
        loading: false,
        show_financial_data: false,
        detailsOpen: false,
        accept() {
            this.loading = true;
            fetch('{{ route('user.inspection.accept', $pendingInspection) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    show_financial_data: this.show_financial_data
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    window.location.reload();
                }
            })
            .finally(() => this.loading = false);
        },
        reject() {
            fetch('{{ route('user.inspection.reject', $pendingInspection) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => this.show = false);
        }
    }"
    x-show="show"
    class="fixed inset-0 z-[200] flex items-center justify-center p-3 sm:p-4 md:p-6 overflow-y-auto bg-slate-950/90 backdrop-blur-xl"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-cloak>

    <div class="w-full max-w-2xl my-auto rounded-2xl sm:rounded-[2.5rem] md:rounded-[3rem] bg-white dark:bg-slate-900 shadow-[0_0_60px_rgba(0,0,0,0.4)] md:shadow-[0_0_100px_rgba(0,0,0,0.5)] border border-white/10 overflow-hidden relative flex-shrink-0"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">

        <!-- Decoration Grid -->
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none dark:opacity-[0.06]" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 16px 16px;"></div>

        <div class="relative p-5 sm:p-8 md:p-12 lg:p-16 text-center">
            {{-- PRO Badge --}}
            @if($isPro)
                <div class="absolute top-4 right-4 sm:top-6 sm:right-6 flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-100 dark:bg-amber-500/20 border border-amber-200 dark:border-amber-500/30">
                    <x-icon name="crown" style="solid" class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400" />
                    <span class="text-[9px] font-black text-amber-700 dark:text-amber-400 uppercase tracking-wider">VIP</span>
                </div>
            @endif

            <!-- Header Icon -->
            <div class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 bg-gradient-to-br from-amber-400 to-orange-600 rounded-2xl sm:rounded-[2rem] md:rounded-[2.5rem] flex items-center justify-center mx-auto mb-4 sm:mb-6 md:mb-8 shadow-2xl shadow-orange-500/20">
                <x-icon name="magnifying-glass-chart" style="solid" class="text-2xl sm:text-3xl md:text-4xl text-white" />
            </div>

            <h3 class="text-xl sm:text-2xl md:text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tighter mb-2 sm:mb-4 leading-tight px-1">
                Autorização de <span class="text-orange-500">Inspeção Remota</span>
            </h3>

            <p class="text-sm sm:text-base md:text-lg text-gray-500 dark:text-gray-400 leading-relaxed mb-4 sm:mb-6 max-w-lg mx-auto px-1">
                O agente <span class="text-slate-900 dark:text-white font-black underline decoration-orange-500/30">{{ $pendingInspection->agent->name }}</span> precisa visualizar o seu painel em tempo real para resolver o chamado <span class="font-black text-primary">#{{ $pendingInspection->ticket_id }}</span>.
            </p>

            <!-- Privacy Toggle -->
            <div class="mb-6 sm:mb-8 p-3 sm:p-4 bg-amber-50 dark:bg-amber-500/5 rounded-xl sm:rounded-2xl border border-amber-200 dark:border-amber-500/20 flex items-center justify-between gap-3 sm:gap-4 cursor-pointer hover:bg-amber-100/50 dark:hover:bg-amber-500/10 transition-colors" @click="show_financial_data = !show_financial_data">
                <div class="flex items-center gap-2.5 sm:gap-3 text-left min-w-0">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl flex items-center justify-center overflow-hidden transition-all flex-shrink-0" :class="show_financial_data ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-slate-200 dark:bg-slate-700 text-slate-400'">
                        <span :class="show_financial_data ? 'scale-110' : 'scale-90'" class="inline-block transition-transform">
                            <x-icon name="chart-line" style="solid" class="w-4 h-4 sm:w-5 sm:h-5 block" />
                        </span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Exibir Dados Financeiros?</p>
                        <p class="text-[9px] sm:text-[10px] text-gray-500 dark:text-gray-400 leading-tight">Permitir que o agente veja saldos, extratos e valores.</p>
                    </div>
                </div>
                <div class="w-11 h-5 sm:w-12 sm:h-6 rounded-full relative transition-colors duration-300 flex-shrink-0" :class="show_financial_data ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600'">
                    <div class="absolute top-0.5 left-0.5 sm:top-1 sm:left-1 w-4 h-4 bg-white rounded-full shadow-sm transition-transform duration-300" :class="show_financial_data ? 'translate-x-6 sm:translate-x-7' : 'translate-x-0'"></div>
                </div>
            </div>

            <!-- Info Cards - Responsive Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 p-5 sm:p-6 md:p-8 bg-slate-50 dark:bg-slate-800/40 rounded-xl sm:rounded-2xl md:rounded-[2.5rem] text-left mb-8 sm:mb-12 border border-black/5 dark:border-white/5 shadow-inner">
                <div class="flex items-start gap-3 sm:gap-4">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
                        <x-icon name="user-shield" style="solid" class="text-emerald-500 w-4 h-4 sm:w-5 sm:h-5" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-[12px] font-black text-slate-900 dark:text-white uppercase tracking-widest mb-0.5 sm:mb-1">Acesso Ético</p>
                        <p class="text-[10px] sm:text-[11px] text-gray-500 dark:text-gray-400 leading-normal">O agente verá o que você vê. Senhas e dados de pagamento permanecem invisíveis.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 sm:gap-4">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-blue-500/10 flex items-center justify-center flex-shrink-0">
                        <x-icon name="bolt" style="solid" class="text-blue-500 w-4 h-4 sm:w-5 sm:h-5" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-[12px] font-black text-slate-900 dark:text-white uppercase tracking-widest mb-0.5 sm:mb-1">Totalmente Seguro</p>
                        <p class="text-[10px] sm:text-[11px] text-gray-500 dark:text-gray-400 leading-normal">Banner no topo durante a sessão. Você tem o controle total.</p>
                    </div>
                </div>
            </div>

            {{-- Saiba mais (expandable) --}}
            <div class="mb-8 sm:mb-10">
                <button type="button" @click="detailsOpen = !detailsOpen" class="flex items-center justify-center gap-2 mx-auto text-xs font-bold text-gray-500 dark:text-gray-400 hover:text-orange-500 dark:hover:text-orange-400 transition-colors">
                    <span x-text="detailsOpen ? 'Ocultar detalhes' : 'Saiba mais sobre a inspeção'"></span>
                    <span class="inline-block transition-transform duration-200" :class="detailsOpen ? 'rotate-180' : ''">
                        <x-icon name="chevron-down" style="solid" class="w-3.5 h-3.5" />
                    </span>
                </button>
                <div x-show="detailsOpen" x-collapse class="mt-4 p-4 sm:p-5 rounded-xl bg-slate-100/80 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/50 text-left">
                    <ul class="space-y-2 text-[11px] sm:text-xs text-gray-600 dark:text-gray-400">
                        <li class="flex items-start gap-2">
                            <x-icon name="check" style="solid" class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" />
                            <span>O agente navega no seu painel como se fosse você, sem acesso a senhas ou alterações críticas.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <x-icon name="check" style="solid" class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" />
                            <span>Você pode revogar a qualquer momento encerrando sua sessão ou aguardando o agente finalizar.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <x-icon name="check" style="solid" class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" />
                            <span>Todas as ações são registradas em auditoria para sua segurança.</span>
                        </li>
                        @if($isPro)
                            <li class="flex items-start gap-2 pt-2 border-t border-amber-200 dark:border-amber-700/50">
                                <x-icon name="crown" style="solid" class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" />
                                <span class="font-semibold text-amber-700 dark:text-amber-400">Vertex PRO:</span> Atendimento VIP com verificação de sessão mais rápida e badge exclusivo no banner.
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row gap-3 sm:gap-5">
                <button @click="reject" class="flex-1 px-6 sm:px-8 py-4 sm:py-5 bg-gray-100 dark:bg-slate-800 text-gray-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all font-black text-[10px] sm:text-xs uppercase tracking-[0.15em] sm:tracking-[0.2em] rounded-2xl sm:rounded-3xl active:scale-95">
                    Recusar Pedido
                </button>
                <button @click="accept" :disabled="loading" class="flex-1 px-6 sm:px-8 py-4 sm:py-5 bg-primary hover:bg-primary-dark text-white font-black text-[10px] sm:text-xs uppercase tracking-[0.15em] sm:tracking-[0.2em] rounded-2xl sm:rounded-3xl shadow-2xl shadow-primary/30 transition-all flex items-center justify-center gap-2 sm:gap-3 active:scale-95 disabled:opacity-50 group">
                   <template x-if="!loading">
                       <span class="flex items-center gap-2 sm:gap-3">
                           <span>Autorizar Acesso</span>
                           <x-icon name="arrow-right" style="solid" class="group-hover:translate-x-1 transition-transform w-4 h-4 sm:w-5 sm:h-5" />
                       </span>
                   </template>
                   <template x-if="loading">
                       <x-icon name="circle-notch" style="solid" class="fa-spin w-5 h-5" />
                   </template>
                </button>
            </div>

            <p class="mt-6 sm:mt-8 flex items-center justify-center gap-1.5 text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                <x-icon name="lock" style="solid" class="w-3 h-3" />
                <span>Criptografia de Ponta a Ponta Ativa</span>
            </p>
        </div>
    </div>
</div>
@endif
