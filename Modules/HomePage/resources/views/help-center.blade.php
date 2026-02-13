<x-homepage::layouts.master>
    <x-homepage::layouts.navbar />

    <main class="min-h-screen bg-slate-50 dark:bg-slate-900 pt-32 pb-20 font-['Poppins']">
        <!-- Hero Search Section -->
        <section class="relative py-20 overflow-hidden">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10">
                <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-primary/5 rounded-full blur-3xl animate-pulse delay-1000"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-5xl font-black text-slate-800 dark:text-white mb-6 tracking-tight">
                    Como podemos <span class="text-primary">ajudar?</span>
                </h1>
                <p class="text-lg text-slate-500 dark:text-slate-400 mb-10 max-w-2xl mx-auto font-medium">
                    Busque por tutoriais, resolva dúvidas frequentes ou aprenda a dominar suas finanças com a VertexContas.
                </p>

                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto relative group">
                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                        <x-icon name="magnifying-glass" class="text-xl" />
                    </div>
                    <input type="text"
                        placeholder="Ex: Como importar extratos..."
                        class="w-full pl-16 pr-6 py-5 bg-white dark:bg-slate-800 border-none rounded-[24px] shadow-2xl shadow-slate-200/50 dark:shadow-none text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium text-lg">
                </div>
            </div>
        </section>

        <!-- Categories Grid -->
        <section class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Category 1 -->
                    <div class="group p-8 bg-white dark:bg-slate-800 rounded-[32px] border border-slate-100 dark:border-slate-700 hover:border-primary/30 transition-all hover:shadow-2xl hover:shadow-primary/5 cursor-pointer">
                        <div class="w-16 h-16 bg-blue-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="rocket-launch" class="text-blue-500 text-3xl" />
                        </div>
                        <h3 class="text-xl font-bold mb-3 dark:text-white">Primeiros Passos</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-4">Aprenda a configurar sua conta e dar os primeiros passos na sua jornada financeira.</p>
                        <span class="text-primary font-bold text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                            Ver artigos <x-icon name="arrow-right" class="text-xs" />
                        </span>
                    </div>

                    <!-- Category 2 -->
                    <div class="group p-8 bg-white dark:bg-slate-800 rounded-[32px] border border-slate-100 dark:border-slate-700 hover:border-primary/30 transition-all hover:shadow-2xl hover:shadow-primary/5 cursor-pointer">
                        <div class="w-16 h-16 bg-emerald-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="shield-check" class="text-emerald-500 text-3xl" />
                        </div>
                        <h3 class="text-xl font-bold mb-3 dark:text-white">Segurança & Privacidade</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-4">Entenda como protegemos seus dados e como gerenciar suas preferências locais.</p>
                        <span class="text-primary font-bold text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                            Ver artigos <x-icon name="arrow-right" class="text-xs" />
                        </span>
                    </div>

                    <!-- Category 3 -->
                    <div class="group p-8 bg-white dark:bg-slate-800 rounded-[32px] border border-slate-100 dark:border-slate-700 hover:border-primary/30 transition-all hover:shadow-2xl hover:shadow-primary/5 cursor-pointer">
                        <div class="w-16 h-16 bg-amber-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="credit-card" class="text-amber-500 text-3xl" />
                        </div>
                        <h3 class="text-xl font-bold mb-3 dark:text-white">Planos & Pagamentos</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-4">Dúvidas sobre assinaturas, métodos de pagamento e benefícios das contas Pro.</p>
                        <span class="text-primary font-bold text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                            Ver artigos <x-icon name="arrow-right" class="text-xs" />
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="py-20" id="faq">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-black text-slate-800 dark:text-white mb-4 tracking-tight">Dúvidas Frequentes</h2>
                    <div class="w-20 h-1.5 bg-primary mx-auto rounded-full"></div>
                </div>

                <div class="space-y-4" x-data="{ activeAccordion: null }">
                    <!-- FAQ 1 -->
                    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 overflow-hidden transition-all">
                        <button @click="activeAccordion = activeAccordion === 1 ? null : 1"
                            class="w-full p-6 text-left flex justify-between items-center group">
                            <span class="font-bold text-slate-700 dark:text-slate-200 group-hover:text-primary transition-colors">Meus dados ficam realmente na minha máquina?</span>
                            <x-icon name="chevron-down" class="text-slate-400 transition-transform duration-300" x-bind:class="activeAccordion === 1 ? 'rotate-180' : ''" />
                        </button>
                        <div x-show="activeAccordion === 1" x-collapse>
                            <div class="px-6 pb-6 text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                                Sim! A VertexContas foi projetada com foco em privacidade absoluta. Todas as suas transações e dados financeiros são armazenados localmente no seu dispositivo. Nós não temos acesso ao seu saldo ou extratos.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 overflow-hidden transition-all">
                        <button @click="activeAccordion = activeAccordion === 2 ? null : 2"
                            class="w-full p-6 text-left flex justify-between items-center group">
                            <span class="font-bold text-slate-700 dark:text-slate-200 group-hover:text-primary transition-colors">Posso importar dados de outros bancos?</span>
                            <x-icon name="chevron-down" class="text-slate-400 transition-transform duration-300" x-bind:class="activeAccordion === 2 ? 'rotate-180' : ''" />
                        </button>
                        <div x-show="activeAccordion === 2" x-collapse>
                            <div class="px-6 pb-6 text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                                Com certeza. Você pode importar arquivos OFX, CSV e Excel exportados do seu banco de forma simples e rápida, tudo processado localmente.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 overflow-hidden transition-all">
                        <button @click="activeAccordion = activeAccordion === 3 ? null : 3"
                            class="w-full p-6 text-left flex justify-between items-center group">
                            <span class="font-bold text-slate-700 dark:text-slate-200 group-hover:text-primary transition-colors">O que acontece se eu trocar de computador?</span>
                            <x-icon name="chevron-down" class="text-slate-400 transition-transform duration-300" x-bind:class="activeAccordion === 3 ? 'rotate-180' : ''" />
                        </button>
                        <div x-show="activeAccordion === 3" x-collapse>
                            <div class="px-6 pb-6 text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                                Você pode realizar um backup manual criptografado dos seus dados a qualquer momento e restaurá-lo na sua nova máquina para manter todo o seu histórico.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Still Need Help -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 mb-20">
            <div class="bg-gradient-to-br from-primary to-primary-dark rounded-[40px] p-10 md:p-20 text-center relative overflow-hidden shadow-2xl shadow-primary/30">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <x-icon name="headset" class="text-9xl text-white" />
                </div>

                <h2 class="text-3xl md:text-4xl font-black text-white mb-6 tracking-tight relative z-10">Ainda precisa de ajuda?</h2>
                <p class="text-white/80 text-lg mb-10 max-w-2xl mx-auto font-medium relative z-10">Nosso time de suporte especializado está pronto para te ajudar a tirar o melhor proveito da plataforma.</p>

                <a href="mailto:suporte@vertexcontas.com" class="inline-flex items-center gap-3 bg-white text-primary px-10 py-5 rounded-2xl text-lg font-black shadow-xl hover:scale-105 active:scale-95 transition-all relative z-10 group">
                    <x-icon name="envelope" />
                    Falar com Suporte
                </a>
            </div>
        </section>
    </main>

    <x-homepage::layouts.footer />
</x-homepage::layouts.master>
