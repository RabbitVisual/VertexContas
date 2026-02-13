@php
    $pendingInspection = \Modules\Core\Models\Inspection::where('user_id', auth()->id())
        ->where('status', 'pending')
        ->latest()
        ->first();
@endphp

@if($pendingInspection)
<div x-data="{
        show: true,
        loading: false,
        show_financial_data: false,
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
    class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-xl"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-cloak>

    <div class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-[3rem] shadow-[0_0_100px_rgba(0,0,0,0.5)] border border-white/10 overflow-hidden relative"
         x-transition:enter="transition ease-out duration-500 scale-95"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100 scale-100">

        <!-- Decoration Grid -->
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;"></div>

        <div class="relative p-10 md:p-16 text-center">
            <!-- Header Icon -->
            <div class="w-24 h-24 bg-gradient-to-br from-amber-400 to-orange-600 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-orange-500/20 animate-bounce">
                <x-icon name="magnifying-glass-chart" style="solid" class="text-4xl text-white" />
            </div>

            <h3 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tighter mb-4 leading-tight">
                Autorização de <span class="text-orange-500">Inspeção Remota</span>
            </h3>

            <p class="text-gray-500 dark:text-gray-400 text-lg leading-relaxed mb-6 max-w-lg mx-auto">
                O agente <span class="text-slate-900 dark:text-white font-black underline decoration-orange-500/30">{{ $pendingInspection->agent->name }}</span> precisa visualizar o seu painel em tempo real para resolver o chamado <span class="font-black text-primary">#{{ $pendingInspection->ticket_id }}</span>.
            </p>

            <!-- Privacy Toggle -->
            <div class="mb-8 p-4 bg-amber-50 dark:bg-amber-500/5 rounded-2xl border border-amber-200 dark:border-amber-500/20 flex items-center justify-between gap-4 cursor-pointer hover:bg-amber-100/50 transition-colors" @click="show_financial_data = !show_financial_data">
                <div class="flex items-center gap-3 text-left">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center overflow-hidden transition-all" :class="show_financial_data ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-slate-200 dark:bg-slate-700 text-slate-400'">
                        <x-icon name="chart-line" style="solid" ::class="show_financial_data ? 'scale-110' : 'scale-90'" />
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Exibir Dados Financeiros?</p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 leading-tight">Permitir que o agente veja saldos, extratos e valores de contas.</p>
                    </div>
                </div>
                <div class="w-12 h-6 rounded-full relative transition-colors duration-300" :class="show_financial_data ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600'">
                    <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow-sm transition-transform duration-300" :class="show_financial_data ? 'translate-x-6' : 'translate-x-0'"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8 bg-slate-50 dark:bg-slate-800/40 rounded-[2.5rem] text-left mb-12 border border-black/5 dark:border-white/5 shadow-inner">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
                        <x-icon name="user-shield" style="solid" class="text-emerald-500" />
                    </div>
                    <div>
                        <p class="text-[12px] font-black text-slate-900 dark:text-white uppercase tracking-widest mb-1">Acesso Ético</p>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-normal">O agente verá o que você vê. Senhas e dados sensíveis de pagamento permanecem invisíveis.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-2xl bg-blue-500/10 flex items-center justify-center flex-shrink-0">
                        <x-icon name="bolt" style="solid" class="text-blue-500" />
                    </div>
                    <div>
                        <p class="text-[12px] font-black text-slate-900 dark:text-white uppercase tracking-widest mb-1">Totalmente Seguro</p>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-normal">Um banner vermelho aparecerá no topo enquanto ele estiver online. Você tem o controle total.</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-5">
                <button @click="reject" class="flex-1 px-8 py-5 bg-gray-100 dark:bg-slate-800 text-gray-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all font-black text-xs uppercase tracking-[0.2em] rounded-3xl active:scale-95">
                    Recusar Pedido
                </button>
                <button @click="accept" :disabled="loading" class="flex-1 px-8 py-5 bg-primary hover:bg-primary-dark text-white font-black text-xs uppercase tracking-[0.2em] rounded-3xl shadow-2xl shadow-primary/30 transition-all flex items-center justify-center gap-3 active:scale-95 disabled:opacity-50 group">
                   <template x-if="!loading">
                       <div class="flex items-center gap-3">
                           <span>Autorizar Acesso</span>
                           <x-icon name="arrow-right" style="solid" class="group-hover:translate-x-1 transition-transform" />
                       </div>
                   </template>
                   <template x-if="loading">
                       <x-icon name="spinner" class="animate-spin" />
                   </template>
                </button>
            </div>

            <p class="mt-8 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                <x-icon name="lock" class="mr-1" /> Criptografia de Ponta a Ponta Ativa
            </p>
        </div>
    </div>
</div>
@endif
