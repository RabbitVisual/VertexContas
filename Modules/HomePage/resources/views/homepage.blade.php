<x-homepage::layouts.master>
    <x-homepage::layouts.navbar />

    <main class="font-['Poppins'] overflow-x-hidden">
        <!-- Hero Section -->
        <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 bg-white dark:bg-slate-900 transition-colors duration-500">
            <!-- Background Decorations -->
            <div class="absolute top-0 right-0 w-1/2 h-1/2 bg-gradient-to-br from-primary/5 to-transparent rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 -z-10"></div>
            <div class="absolute bottom-0 left-0 w-1/3 h-1/3 bg-gradient-to-tr from-primary/5 to-transparent rounded-full blur-3xl translate-y-1/2 -translate-x-1/2 -z-10"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center lg:text-left flex flex-col lg:flex-row items-center gap-16">
                <div class="w-full lg:w-3/5 space-y-8">
                    @auth
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/10 text-emerald-500 text-xs font-bold uppercase tracking-wider backdrop-blur-sm animate-fade-in-down">
                            <x-icon name="circle-user" />
                            Bem-vindo de volta, {{ Auth::user()->first_name }}!
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider backdrop-blur-sm animate-fade-in-down">
                            <x-icon name="shield-check" />
                            Gestão 100% Local e Segura
                        </div>
                    @endauth

                    <h1 class="text-5xl lg:text-7xl font-black text-slate-800 dark:text-white leading-[1.1] animate-fade-in-up">
                        Domine cada <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-primary-dark">centavo</span> da sua vida.
                    </h1>

                    <p class="text-xl text-slate-500 dark:text-slate-400 leading-relaxed max-w-2xl mx-auto lg:mx-0 animate-fade-in-up delay-75">
                        Gerencie receitas, despesas, orçamentos e metas em uma interface profissional, projetada para quem busca liberdade financeira total sem depender de conexões externas.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-4 animate-fade-in-up delay-150">
                        @auth
                            @php
                                $dashboardRoute = 'user.index';
                                if(Auth::user()->hasRole('admin')) $dashboardRoute = 'admin.index';
                                elseif(Auth::user()->hasRole('support')) $dashboardRoute = 'support.index';
                            @endphp
                            <a href="{{ route($dashboardRoute) }}" class="w-full sm:w-auto bg-primary hover:bg-primary-dark text-white px-10 py-4 rounded-2xl text-lg font-bold shadow-2xl shadow-primary/40 transform hover:-translate-y-1 transition-all flex items-center justify-center gap-3 group decoration-transparent">
                                Acessar Meu Painel
                                <x-icon name="grid-2" class="group-hover:scale-110 transition-transform" />
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="w-full sm:w-auto bg-primary hover:bg-primary-dark text-white px-10 py-4 rounded-2xl text-lg font-bold shadow-2xl shadow-primary/40 transform hover:-translate-y-1 transition-all flex items-center justify-center gap-3 group decoration-transparent">
                                Criar Conta Grátis
                                <x-icon name="arrow-right" class="group-hover:translate-x-1 transition-transform" />
                            </a>
                        @endauth
                        <a href="{{ route('help-center') }}" class="w-full sm:w-auto bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-10 py-4 rounded-2xl text-lg font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-all flex items-center justify-center gap-3 decoration-transparent">
                            Ver Central de Ajuda
                        </a>
                    </div>

                    <div class="flex items-center justify-center lg:justify-start gap-8 pt-8 opacity-60">
                        <div class="flex flex-col items-center lg:items-start">
                            <span class="text-2xl font-black dark:text-white">+R$ 0</span>
                            <span class="text-xs font-bold uppercase tracking-widest">Renda Bruta</span>
                        </div>
                        <div class="w-px h-8 bg-slate-300 dark:bg-slate-700"></div>
                        <div class="flex flex-col items-center lg:items-start">
                            <span class="text-2xl font-black dark:text-white">0%</span>
                            <span class="text-xs font-bold uppercase tracking-widest">Economia</span>
                        </div>
                    </div>
                </div>

                <!-- Hero Mockup/Illustration -->
                <div class="w-full lg:w-2/5 relative animate-fade-in-right">
                    <div class="relative bg-gradient-to-br from-slate-100 to-white dark:from-slate-800 dark:to-slate-900 p-6 rounded-3xl shadow-[0_32px_64px_-16px_rgba(0,0,0,0.2)] border border-slate-200 dark:border-slate-700 transform lg:rotate-2 group hover:rotate-0 transition-transform duration-700">
                        @auth
                            <!-- Auth Welcome Card -->
                            <div class="absolute -top-10 -left-10 w-72 bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl p-6 border border-slate-100 dark:border-slate-800 z-10 animate-fade-in-up rotate-[-6deg] group-hover:rotate-0 transition-transform duration-500">
                                <div class="flex flex-col items-center text-center">
                                    <div class="relative mb-4">
                                        @if(Auth::user()->photo)
                                            <img src="{{ asset('storage/' . Auth::user()->photo) }}" class="w-24 h-24 rounded-[2rem] object-cover ring-4 ring-primary/20">
                                        @else
                                            <div class="w-24 h-24 rounded-[2rem] bg-primary/10 text-primary flex items-center justify-center font-black text-4xl ring-4 ring-primary/20">
                                                {{ substr(Auth::user()->first_name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="absolute -bottom-2 -right-2 bg-primary text-white p-2 rounded-2xl shadow-lg">
                                            <x-icon name="check" class="text-xs" />
                                        </div>
                                    </div>

                                    <h4 class="text-lg font-black text-slate-800 dark:text-white leading-tight mb-1">{{ Auth::user()->full_name }}</h4>

                                    @php
                                        $roleName = 'Usuário Free';
                                        if(Auth::user()->hasRole('admin')) $roleName = 'Administrador';
                                        elseif(Auth::user()->hasRole('support')) $roleName = 'Agente de Suporte';
                                        elseif(Auth::user()->membership === 'pro') $roleName = 'Usuário Pro';
                                    @endphp
                                    <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest rounded-full mb-6">
                                        {{ $roleName }}
                                    </span>

                                    <div class="w-full space-y-3 pt-6 border-t border-slate-50 dark:border-slate-800">
                                        <div class="flex justify-between items-center text-[10px] font-bold">
                                            <span class="text-slate-400 uppercase tracking-widest">Nascimento</span>
                                            <span class="text-slate-600 dark:text-gray-300">{{ Auth::user()->birth_date ? Auth::user()->birth_date->format('d/m/Y') : 'Não informado' }}</span>
                                        </div>
                                        <div class="flex justify-between items-center text-[10px] font-bold">
                                            <span class="text-slate-400 uppercase tracking-widest">Status</span>
                                            <span class="text-emerald-500 flex items-center gap-1 uppercase tracking-widest">
                                                <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span>
                                                Online
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endauth

                        <!-- Simulated Card -->
                        <div class="bg-primary p-6 rounded-2xl mb-6 text-white overflow-hidden relative">
                            <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                            <div class="flex justify-between items-start mb-8">
                                <span class="text-xs font-bold uppercase opacity-80">Saldo Principal</span>
                                <x-icon name="nfc" class="text-xl opacity-60" />
                            </div>
                            <div class="text-3xl font-black mb-1">R$ 15.750,00</div>
                            <div class="text-xs font-medium opacity-60">Vertex Oh Pro - Platinum</div>
                        </div>

                        <!-- Simulated Transations -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-green-500/10 text-green-500 flex items-center justify-center">
                                        <x-icon name="plus" style="solid" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold dark:text-white">Renda Mensal</div>
                                        <div class="text-xs text-slate-400">Hoje, 10:45</div>
                                    </div>
                                </div>
                                <span class="text-sm font-black text-green-500">+ R$ 5.000,00</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-red-500/10 text-red-500 flex items-center justify-center">
                                        <x-icon name="cart-shopping" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold dark:text-white">Supermercado</div>
                                        <div class="text-xs text-slate-400">Ontem, 20:30</div>
                                    </div>
                                </div>
                                <span class="text-sm font-black text-red-500">- R$ 450,20</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-24 bg-slate-50 dark:bg-slate-950 transition-colors duration-500 relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
                    <h2 class="text-4xl font-black text-slate-800 dark:text-white">Tudo o que você precisa</h2>
                    <p class="text-lg text-slate-500 dark:text-slate-400">Gerencie todas as nuances do seu dinheiro sem sair de casa, com ferramentas que facilitam o planejamento financeiro.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="group p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-primary/30 transition-all hover:shadow-2xl hover:shadow-primary/5">
                        <div class="w-14 h-14 bg-indigo-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="money-bill-trend-up" class="text-indigo-500 text-2xl" />
                        </div>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Renda & Despesas</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">Categorize suas entradas e saídas de forma inteligente. Saiba exatamente para onde cada real está indo.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="group p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-primary/30 transition-all hover:shadow-2xl hover:shadow-primary/5">
                        <div class="w-14 h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="bullseye-arrow" class="text-emerald-500 text-2xl" />
                        </div>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Metas Financeiras</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">Crie planos para sua reserva de emergência, viagens ou a compra de um carro. Acompanhe o progresso em tempo real.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="group p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-primary/30 transition-all hover:shadow-2xl hover:shadow-primary/5">
                        <div class="w-14 h-14 bg-amber-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="calculator" class="text-amber-500 text-2xl" />
                        </div>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Orçamentos Conscientes</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">Defina limites mensais para cada categoria e receba alertas para evitar gastos impulsivos.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="group p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-primary/30 transition-all hover:shadow-2xl hover:shadow-primary/5">
                        <div class="w-14 h-14 bg-purple-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="chart-pie-simple" class="text-purple-500 text-2xl" />
                        </div>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Relatórios Detalhados</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">Visualize sua saúde financeira através de gráficos dinâmicos e exporte dados para análises profundas.</p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="group p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-primary/30 transition-all hover:shadow-2xl hover:shadow-primary/5">
                        <div class="w-14 h-14 bg-rose-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="lock" class="text-rose-500 text-2xl" />
                        </div>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Dados 100% Locais</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">Seus dados financeiros nunca saem da sua máquina. Privacidade e segurança absoluta para sua tranquilidade.</p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="group p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-primary/30 transition-all hover:shadow-2xl hover:shadow-primary/5">
                        <div class="w-14 h-14 bg-blue-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="moon-stars" class="text-blue-500 text-2xl" />
                        </div>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Interface Adaptativa</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">Trabalhe confortavelmente em qualquer iluminação com suporte nativo a Dark Mode premium.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 bg-white dark:bg-slate-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gradient-to-br from-primary to-primary-dark rounded-[40px] p-12 lg:p-20 relative overflow-hidden text-center text-white shadow-2xl shadow-primary/25">
                    <div class="absolute inset-0 opacity-10 pointer-events-none">
                        <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full blur-3xl transform -translate-x-1/2 -translate-y-1/2"></div>
                        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl transform translate-x-1/3 translate-y-1/3"></div>
                    </div>

                    <div class="relative z-10 space-y-8 max-w-3xl mx-auto">
                        <h2 class="text-4xl lg:text-6xl font-black">Pronto para transformar sua finanças?</h2>
                        <p class="text-lg opacity-80 font-medium">Cadastre-se hoje e comece a trilhar seu caminho para a prosperidade com organização e inteligência.</p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                            <a href="{{ route('register') }}" class="w-full sm:w-auto bg-white text-primary px-12 py-5 rounded-2xl text-xl font-bold hover:shadow-xl hover:scale-105 transition-all">
                                Criar Conta Agora
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <x-homepage::layouts.footer />

    <style>
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-in-down {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-in-right {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.8s ease-out forwards; }
        .animate-fade-in-down { animation: fade-in-down 0.8s ease-out forwards; }
        .animate-fade-in-right { animation: fade-in-right 1s ease-out forwards; }
    </style>
</x-homepage::layouts.master>
