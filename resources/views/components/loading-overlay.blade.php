<div x-data="{
    loading: false,
    timeout: null,
    showTime: null,
    minDuration: 850, // Minimum time in ms to show the loader to avoid flickering
    message: 'Preparando seu ambiente...',
    financialMessages: [
        'Conciliando lançamentos...',
        'Auditando registros fiscais...',
        'Sincronizando extratos bancários...',
        'Processando fluxos de caixa...',
        'Validando notas fiscais...',
        'Atualizando balanço patrimonial...',
        'Consolidando metas financeiras...'
    ],
    messageInterval: null,

    init() {
        const stop = () => {
            const now = Date.now();
            const elapsed = this.showTime ? now - this.showTime : 0;

            // If it hasn't been shown long enough, defer the stop
            if (this.loading && elapsed < this.minDuration) {
                setTimeout(() => stop(), this.minDuration - elapsed);
                return;
            }

            if (this.timeout) clearTimeout(this.timeout);
            if (this.messageInterval) clearInterval(this.messageInterval);
            this.timeout = null;
            this.loading = false;
            this.showTime = null;
        };

        const start = (msg = null) => {
            if (this.loading) return; // Already loading

            this.message = msg || this.financialMessages[0];

            // Rotate financial messages if generic loading
            if (!msg) {
                let msgIdx = 0;
                this.messageInterval = setInterval(() => {
                    msgIdx = (msgIdx + 1) % this.financialMessages.length;
                    this.message = this.financialMessages[msgIdx];
                }, 2500);
            }

            // Start almost immediately (20ms) to beat the redirect/load but avoid frame-one flicker
            this.timeout = setTimeout(() => {
                this.loading = true;
                this.showTime = Date.now();
            }, 20);
        };

        window.addEventListener('beforeunload', () => start('Salvando alterações e navegando...'));
        window.addEventListener('submit', (e) => {
            if (e.target.hasAttribute('data-no-loading')) return;
            start('Processando dados financeiros...');
        });
        window.addEventListener('pageshow', stop);
        window.addEventListener('load', stop);
        window.addEventListener('DOMContentLoaded', stop);
        window.addEventListener('stop-loading', stop);
        window.addEventListener('start-loading', (e) => start(e.detail?.message));

        // Auto-stop safety
        $watch('loading', v => { if (v) setTimeout(() => { if (this.loading) stop(); }, 30000); });
        stop();
    }
}"
    x-show="loading"
    x-cloak
    role="alert"
    aria-busy="true"
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/95 dark:bg-slate-950/98 backdrop-blur-xl overflow-hidden"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-105"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95">

    <!-- Spreadsheet Grid Background -->
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05] pointer-events-none bg-grid"></div>

    <!-- Easter Egg: Moving Selection Box -->
    <div class="absolute w-20 h-10 border-2 border-primary/30 rounded shadow-[0_0_15px_rgba(var(--color-primary),0.2)] animate-excel-selection pointer-events-none"></div>

    <!-- Background Floating Symbols -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none select-none opacity-20 dark:opacity-30">
        <span class="absolute top-1/4 left-1/4 text-4xl font-black text-primary/10 animate-float-slow delay-100">R$</span>
        <span class="absolute top-1/3 right-1/4 text-6xl font-black text-primary/5 animate-float delay-500">%</span>
        <span class="absolute bottom-1/4 left-1/3 text-5xl font-black text-primary/10 animate-float-slow-reverse delay-300">$</span>
        <span class="absolute top-2/3 right-1/3 text-3xl font-black text-primary/10 animate-float delay-700">€</span>
    </div>

    <div class="relative flex flex-col items-center">
        <!-- Main Creative Animation -->
        <div class="relative mb-12">
            <!-- Glow Effect -->
            <div class="absolute -inset-10 bg-primary/20 blur-[60px] rounded-full animate-pulse"></div>

            <!-- Document/Ledger Container -->
            <div class="relative w-40 h-40 bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-800 flex items-center justify-center rotate-3 group overflow-hidden">
                <!-- Inner Spreadsheet Layout (Abstract) -->
                <div class="absolute inset-0 p-4 space-y-2 opacity-10">
                    <div class="h-2 w-full bg-slate-400 rounded-full"></div>
                    <div class="h-2 w-3/4 bg-slate-400 rounded-full"></div>
                    <div class="grid grid-cols-4 gap-2 pt-2">
                        <div class="h-4 bg-primary/40 rounded"></div>
                        <div class="h-4 bg-slate-400 rounded"></div>
                        <div class="h-4 bg-slate-400 rounded"></div>
                        <div class="h-4 bg-emerald-400/40 rounded"></div>
                    </div>
                </div>

                <!-- Animated Financial Icon -->
                <div class="relative flex flex-col items-center text-primary">
                    <div class="animate-ledger-io">
                        <x-icon name="receipt-tax" style="duotone" class="text-6xl drop-shadow-lg" />
                    </div>

                    <!-- Floating Coins/Tokens Around -->
                    <div class="absolute -top-4 -right-4 animate-bounce delay-100">
                        <x-icon name="circle-dollar-to-slot" style="solid" class="text-2xl text-emerald-500" />
                    </div>
                    <div class="absolute -bottom-2 -left-4 animate-bounce delay-300">
                        <x-icon name="chart-pie-simple" style="duotone" class="text-2xl text-primary-dark" />
                    </div>
                </div>
            </div>

            <!-- Dynamic Percentage Indicator (Visual only) -->
            <div class="absolute -bottom-4 -right-4 bg-emerald-500 text-white px-3 py-1.5 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl border-x-4 border-white dark:border-slate-900 animate-fade-in">
                Auditando...
            </div>
        </div>

        <!-- Typography & Status -->
        <div class="flex flex-col items-center text-center space-y-4 px-6">
            <h3 x-text="message" class="text-2xl font-black text-slate-800 dark:text-white tracking-tight animate-pulse min-h-[2rem]"></h3>

            <p class="text-slate-400 dark:text-slate-500 text-xs font-bold uppercase tracking-[0.3em]">
                Vertex Financial Security Layer
            </p>

            <!-- Sophisticated Progress Indicator -->
            <div class="flex items-center gap-1.5 pt-4">
                @foreach([0, 150, 300, 450] as $delay)
                    <div class="w-1.5 h-1.5 bg-primary rounded-full animate-bounce" style="animation-delay: {{ $delay }}ms"></div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .bg-grid {
        background-size: 50px 50px;
        background-image:
            linear-gradient(to right, rgba(0,0,0,0.05) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(0,0,0,0.05) 1px, transparent 1px);
    }
    .dark .bg-grid {
        background-image:
            linear-gradient(to right, rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(255,255,255,0.03) 1px, transparent 1px);
    }

    @keyframes excel-selection {
        0% { transform: translate(50px, 100px); }
        25% { transform: translate(250px, 150px); }
        50% { transform: translate(150px, 400px); }
        75% { transform: translate(500px, 200px); }
        100% { transform: translate(50px, 100px); }
    }

    @keyframes ledger-io {
        0%, 100% { transform: translateY(0) rotate(0); }
        50% { transform: translateY(-10px) rotate(-5deg); }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-30px) scale(1.1); }
    }

    @keyframes float-slow {
        0%, 100% { transform: translateY(0) translateX(0); }
        50% { transform: translateY(-50px) translateX(20px); }
    }

    @keyframes float-slow-reverse {
        0%, 100% { transform: translateY(0) translateX(0); }
        50% { transform: translateY(40px) translateX(-30px); }
    }

    .animate-excel-selection { animation: excel-selection 8s infinite ease-in-out; }
    .animate-ledger-io { animation: ledger-io 3s infinite ease-in-out; }
    .animate-float { animation: float 5s infinite ease-in-out; }
    .animate-float-slow { animation: float-slow 7s infinite ease-in-out; }
    .animate-float-slow-reverse { animation: float-slow-reverse 9s infinite ease-in-out; }
</style>
