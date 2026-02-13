<x-homepage::layouts.master title="Dúvidas Frequentes - Vertex Contas">
    @push('styles')
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @endpush

    <x-homepage::layouts.navbar />

    <!-- Hero Section -->
    <div class="relative bg-white dark:bg-slate-900 pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary/10 via-background to-background"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col items-center text-center max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-sm font-medium mb-6">
                    <x-icon name="circle-question" style="duotone" />
                    <span>Perguntas Frequentes</span>
                </div>

                <h1 class="text-4xl md:text-5xl font-black text-slate-800 dark:text-white mb-6 tracking-tight">
                    Como podemos <span class="text-primary bg-clip-text text-transparent bg-gradient-to-r from-primary to-primary-dark">ajudar você?</span>
                </h1>

                <p class="text-lg text-slate-600 dark:text-slate-300 mb-10 leading-relaxed max-w-2xl">
                    Encontre respostas rápidas para as perguntas mais comuns sobre o Vertex Contas e aprenda a tirar o máximo da plataforma.
                </p>

                <!-- Search Bar -->
                <div class="w-full max-w-2xl relative group" x-data="{ query: '' }">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <x-icon name="magnifying-glass" class="text-slate-400 group-focus-within:text-primary transition-colors text-lg" />
                    </div>
                    <input type="text"
                        x-model="query"
                        class="w-full bg-white dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white rounded-2xl py-4 pl-12 pr-4 shadow-xl shadow-slate-200/50 dark:shadow-none focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-lg placeholder-slate-400"
                        placeholder="Digite sua dúvida aqui (ex: como importar dados, segurança, backup)...">
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Content -->
    <div class="py-20 bg-slate-50 dark:bg-slate-950/50" x-data="{ activeCategory: 'geral', activeQuestion: null }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                <!-- Sidebar Categories -->
                <div class="lg:col-span-3">
                    <div class="sticky top-24 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 p-2">
                        <nav class="space-y-1">
                            @foreach([
                                'geral' => ['icon' => 'grid-2', 'label' => 'Geral'],
                                'financeiro' => ['icon' => 'wallet', 'label' => 'Financeiro'],
                                'relatorios' => ['icon' => 'chart-pie', 'label' => 'Relatórios'],
                                'seguranca' => ['icon' => 'shield-check', 'label' => 'Segurança']
                            ] as $key => $cat)
                            <button @click="activeCategory = '{{ $key }}'; activeQuestion = null"
                                :class="activeCategory === '{{ $key }}' ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-sm">
                                <x-icon name="{{ $cat['icon'] }}" />
                                {{ $cat['label'] }}
                            </button>
                            @endforeach
                        </nav>
                    </div>
                </div>

                <!-- Questions List -->
                <div class="lg:col-span-9 space-y-4">

                    <!-- Geral -->
                    <div x-show="activeCategory === 'geral'" x-collapse>
                        <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                            <x-icon name="grid-2" class="text-primary" /> Perguntas Gerais
                        </h2>

                        <div class="space-y-4">
                            @php
                                $geralQuestions = [
                                    'O Vertex Contas é gratuito?' => 'Sim! O Vertex Contas possui uma versão gratuita robusta para uso pessoal. Também oferecemos planos Premium para quem precisa de recursos avançados de automação e relatórios detalhados.',
                                    'Preciso de internet para usar?' => 'O Vertex Contas é uma aplicação web instalada localmente (Local-First). Você precisa de internet apenas para atualizações do sistema, mas seus dados e o uso diário são totalmente locais e funcionam offline.',
                                    'Como faço meu cadastro?' => 'Basta clicar no botão "Criar Conta Grátis" no canto superior direito. Preencha seus dados básicos e comece a usar imediatamente.'
                                ];
                            @endphp
                            @foreach($geralQuestions as $q => $a)
                            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
                                <button @click="activeQuestion = activeQuestion === '{{ Str::slug($q) }}' ? null : '{{ Str::slug($q) }}'"
                                    class="w-full px-6 py-5 flex items-center justify-between text-left group">
                                    <span class="font-bold text-slate-700 dark:text-slate-200 group-hover:text-primary transition-colors">{{ $q }}</span>
                                    <x-icon name="chevron-down" class="text-slate-400 transition-transform duration-300"
                                        x-bind:class="activeQuestion === '{{ Str::slug($q) }}' ? 'rotate-180 text-primary' : ''" />
                                </button>
                                <div x-show="activeQuestion === '{{ Str::slug($q) }}'" x-collapse>
                                    <div class="px-6 pb-6 text-slate-500 dark:text-slate-400 leading-relaxed border-t border-slate-50 dark:border-slate-800/50 pt-4 mt-2">
                                        {{ $a }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Financeiro -->
                    <div x-show="activeCategory === 'financeiro'" x-cloak>
                        <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                            <x-icon name="wallet" class="text-primary" /> Financeiro
                        </h2>
                        <div class="space-y-4">
                             @php
                                $finQuestions = [
                                    'Posso cadastrar cartões de crédito?' => 'Sim, você pode gerenciar múltiplos cartões de crédito, definir limites, datas de fechamento e vencimento.',
                                    'Como funcionam as categorias?' => 'As categorias ajudam a organizar seus gastos. Você pode criar categorias personalizadas e subcategorias para um controle mais granular.',
                                    'Posso importar extratos bancários?' => 'Em breve! Estamos trabalhando na funcionalidade de importação de OFX e integração via Open Finance.'
                                ];
                            @endphp
                            @foreach($finQuestions as $q => $a)
                            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
                                <button @click="activeQuestion = activeQuestion === '{{ Str::slug($q) }}' ? null : '{{ Str::slug($q) }}'"
                                    class="w-full px-6 py-5 flex items-center justify-between text-left group">
                                    <span class="font-bold text-slate-700 dark:text-slate-200 group-hover:text-primary transition-colors">{{ $q }}</span>
                                    <x-icon name="chevron-down" class="text-slate-400 transition-transform duration-300"
                                        x-bind:class="activeQuestion === '{{ Str::slug($q) }}' ? 'rotate-180 text-primary' : ''" />
                                </button>
                                <div x-show="activeQuestion === '{{ Str::slug($q) }}'" x-collapse>
                                    <div class="px-6 pb-6 text-slate-500 dark:text-slate-400 leading-relaxed border-t border-slate-50 dark:border-slate-800/50 pt-4 mt-2">
                                        {{ $a }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Relatórios -->
                    <div x-show="activeCategory === 'relatorios'" x-cloak>
                        <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                            <x-icon name="chart-pie" class="text-primary" /> Relatórios
                        </h2>
                        <div class="space-y-4">
                             @php
                                $relQuestions = [
                                    'Quais tipos de gráficos estão disponíveis?' => 'Oferecemos gráficos de pizza para distribuição de gastos, barras para evolução mensal e linhas para fluxo de caixa.',
                                    'Posso exportar os relatórios?' => 'Sim, você pode exportar seus relatórios e extratos para PDF e Excel (CSV) para análise externa.'
                                ];
                            @endphp
                            @foreach($relQuestions as $q => $a)
                            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
                                <button @click="activeQuestion = activeQuestion === '{{ Str::slug($q) }}' ? null : '{{ Str::slug($q) }}'"
                                    class="w-full px-6 py-5 flex items-center justify-between text-left group">
                                    <span class="font-bold text-slate-700 dark:text-slate-200 group-hover:text-primary transition-colors">{{ $q }}</span>
                                    <x-icon name="chevron-down" class="text-slate-400 transition-transform duration-300"
                                        x-bind:class="activeQuestion === '{{ Str::slug($q) }}' ? 'rotate-180 text-primary' : ''" />
                                </button>
                                <div x-show="activeQuestion === '{{ Str::slug($q) }}'" x-collapse>
                                    <div class="px-6 pb-6 text-slate-500 dark:text-slate-400 leading-relaxed border-t border-slate-50 dark:border-slate-800/50 pt-4 mt-2">
                                        {{ $a }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Segurança -->
                    <div x-show="activeCategory === 'seguranca'" x-cloak>
                        <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                            <x-icon name="shield-check" class="text-primary" /> Segurança
                        </h2>
                        <div class="space-y-4">
                             @php
                                $secQuestions = [
                                    'Meus dados ficam na nuvem?' => 'Não por padrão. A filosofia do Vertex Contas é Local-First. Seus dados residem primariamente no seu dispositivo, garantindo total privacidade e controle.',
                                    'O sistema faz backup automático?' => 'Sim, o sistema realiza backups locais automáticos. Você também pode configurar backups para serviços de nuvem de sua preferência (Google Drive, Dropbox) se desejar.',
                                    'Como recupero minha senha?' => 'Utilize a função "Esqueci minha senha" na tela de login. Enviaremos um link seguro para o seu e-mail cadastrado.'
                                ];
                            @endphp
                            @foreach($secQuestions as $q => $a)
                            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
                                <button @click="activeQuestion = activeQuestion === '{{ Str::slug($q) }}' ? null : '{{ Str::slug($q) }}'"
                                    class="w-full px-6 py-5 flex items-center justify-between text-left group">
                                    <span class="font-bold text-slate-700 dark:text-slate-200 group-hover:text-primary transition-colors">{{ $q }}</span>
                                    <x-icon name="chevron-down" class="text-slate-400 transition-transform duration-300"
                                        x-bind:class="activeQuestion === '{{ Str::slug($q) }}' ? 'rotate-180 text-primary' : ''" />
                                </button>
                                <div x-show="activeQuestion === '{{ Str::slug($q) }}'" x-collapse>
                                    <div class="px-6 pb-6 text-slate-500 dark:text-slate-400 leading-relaxed border-t border-slate-50 dark:border-slate-800/50 pt-4 mt-2">
                                        {{ $a }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Contact CTA -->
    <div class="bg-white dark:bg-slate-900 py-20 border-t border-slate-100 dark:border-slate-800">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <div class="mb-8 flex justify-center">
                <div class="w-16 h-16 bg-blue-50 dark:bg-slate-800 rounded-full flex items-center justify-center animate-bounce">
                    <x-icon name="headset" module="homepage" class="text-3xl text-primary" />
                </div>
            </div>
            <h2 class="text-3xl font-bold text-slate-800 dark:text-white mb-4">Ainda não encontrou o que procura?</h2>
            <p class="text-slate-600 dark:text-slate-400 mb-8 max-w-2xl mx-auto">
                Nossa equipe de suporte está pronta para te ajudar. Entre em contato conosco e responderemos o mais breve possível.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-8 py-3 rounded-xl font-bold transition-all shadow-lg shadow-primary/25">
                    <x-icon name="whatsapp" style="brands" />
                    Falar no WhatsApp
                </a>
                <a href="#" class="inline-flex items-center gap-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 px-8 py-3 rounded-xl font-bold transition-all">
                    <x-icon name="envelope" />
                    Enviar E-mail
                </a>
            </div>
        </div>
    </div>

    <x-homepage::layouts.footer />
</x-homepage::layouts.master>
