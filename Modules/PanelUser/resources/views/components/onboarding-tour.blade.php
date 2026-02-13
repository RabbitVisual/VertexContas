@props(['user'])

<div x-data="onboardingTour()" x-init="initTour()" x-show="show" x-cloak
     class="relative z-50">

    <!-- Backdrop -->
    <div x-show="step > 0"
         x-transition.opacity
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40"></div>

    <!-- Step 1: Welcome Modal -->
    <div x-show="step === 1"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         class="fixed inset-0 flex items-center justify-center z-50 pointer-events-none">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl p-8 max-w-lg w-full mx-4 text-center pointer-events-auto border border-gray-100 dark:border-gray-700">
            <div class="mb-6 flex justify-center">
                <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center">
                    <x-logo class="w-12 h-12" />
                </div>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Bem-vindo ao Vertex Contas! 🚀</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                Estamos felizes em tê-lo aqui. Preparamos um tour rápido para você conhecer as principais funcionalidades e começar com o pé direito.
            </p>
            <button @click="nextStep()" class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3 px-6 rounded-xl transition-transform hover:scale-105 shadow-lg flex items-center justify-center gap-2">
                Começar Tour <x-icon name="arrow-right" class="w-4 h-4" />
            </button>
            <button @click="skipTour()" class="mt-4 text-sm text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                Pular introdução
            </button>
        </div>
    </div>

    <!-- Step 2: Accounts (Tooltip) -->
    <div x-show="step === 2"
         x-transition
         class="fixed left-64 top-28 z-50 ml-6 pointer-events-auto hidden md:block">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 w-80 border-l-4 border-l-primary relative">
            <div class="absolute -left-2 top-6 w-4 h-4 bg-white dark:bg-slate-800 transform rotate-45 border-l border-b border-gray-100 dark:border-gray-700"></div>
            <div class="flex items-start gap-4">
                <div class="bg-primary/10 p-2 rounded-lg text-primary">
                    <x-icon name="wallet" class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Minhas Contas</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Comece cadastrando sua conta bancária ou saldo em dinheiro para rastrear seu patrimônio.</p>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-400">Passo 2 de 4</span>
                        <button @click="nextStep()" class="text-sm bg-primary text-white px-4 py-1.5 rounded-lg hover:bg-primary-dark transition-colors">
                            Próximo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Fallback for Step 2 -->
    <div x-show="step === 2" class="fixed inset-x-0 bottom-0 p-4 z-50 md:hidden">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl p-6 border-t-4 border-t-primary">
             <h3 class="font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-2"><x-icon name="wallet" class="text-primary"/> Minhas Contas</h3>
             <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Cadastre suas contas bancárias aqui.</p>
             <button @click="nextStep()" class="w-full bg-primary text-white py-2 rounded-lg">Próximo</button>
        </div>
    </div>

    <!-- Step 3: Goals (Tooltip) -->
    <div x-show="step === 3"
         x-transition
         class="fixed left-64 top-64 z-50 ml-6 pointer-events-auto hidden md:block">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 w-80 border-l-4 border-l-purple-500 relative">
            <div class="absolute -left-2 top-6 w-4 h-4 bg-white dark:bg-slate-800 transform rotate-45 border-l border-b border-gray-100 dark:border-gray-700"></div>
            <div class="flex items-start gap-4">
                <div class="bg-purple-100 dark:bg-purple-900/30 p-2 rounded-lg text-purple-600">
                    <x-icon name="bullseye" class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Metas & Objetivos</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Defina quanto quer economizar. Nós te ajudaremos com barras de progresso automáticas!</p>
                    <div class="flex justify-between items-center">
                         <span class="text-xs text-gray-400">Passo 3 de 4</span>
                        <button @click="nextStep()" class="text-sm bg-purple-600 text-white px-4 py-1.5 rounded-lg hover:bg-purple-700 transition-colors">
                            Próximo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Fallback for Step 3 -->
    <div x-show="step === 3" class="fixed inset-x-0 bottom-0 p-4 z-50 md:hidden">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl p-6 border-t-4 border-t-purple-500">
             <h3 class="font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-2"><x-icon name="bullseye" class="text-purple-500"/> Metas</h3>
             <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Defina seus objetivos financeiros.</p>
             <button @click="nextStep()" class="w-full bg-purple-600 text-white py-2 rounded-lg">Próximo</button>
        </div>
    </div>

    <!-- Step 4: Premium (Tooltip) -->
    <div x-show="step === 4"
         x-transition
         class="fixed left-64 bottom-48 z-50 ml-6 pointer-events-auto hidden md:block">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 w-80 border-l-4 border-l-amber-500 relative">
            <div class="absolute -left-2 top-6 w-4 h-4 bg-white dark:bg-slate-800 transform rotate-45 border-l border-b border-gray-100 dark:border-gray-700"></div>
            <div class="flex items-start gap-4">
                <div class="bg-amber-100 dark:bg-amber-900/30 p-2 rounded-lg text-amber-600">
                    <x-icon name="star" style="solid" class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Seja PRO</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Libere relatórios avançados e limites ilimitados com o plano PRO.</p>
                    <div class="flex justify-between items-center">
                         <span class="text-xs text-gray-400">Passo 4 de 4</span>
                        <button @click="completeTour()" class="text-sm bg-amber-500 text-white px-4 py-1.5 rounded-lg hover:bg-amber-600 transition-colors shadow-lg shadow-amber-500/30">
                            Concluir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Fallback for Step 4 -->
    <div x-show="step === 4" class="fixed inset-x-0 bottom-0 p-4 z-50 md:hidden">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl p-6 border-t-4 border-t-amber-500">
             <h3 class="font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-2"><x-icon name="star" class="text-amber-500"/> Premium</h3>
             <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Libere todo o potencial com o PRO.</p>
             <button @click="completeTour()" class="w-full bg-amber-500 text-white py-2 rounded-lg">Concluir</button>
        </div>
    </div>

</div>

<script>
    function onboardingTour() {
        return {
            step: 1,
            show: {{ !$user->onboarding_completed ? 'true' : 'false' }},

            initTour() {
                if(this.show) {
                    document.body.style.overflow = 'hidden'; // Prevent scrolling during tour
                }
            },

            nextStep() {
                this.step++;
            },

            async completeTour() {
                // Optimistic UI update
                this.show = false;
                document.body.style.overflow = 'auto';

                try {
                    await fetch('{{ route('paneluser.onboarding.complete') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                } catch (error) {
                    console.error('Failed to complete onboarding', error);
                }
            },

            async skipTour() {
                await this.completeTour(); // Treat skip as complete so it doesn't show again
            }
        }
    }
</script>
