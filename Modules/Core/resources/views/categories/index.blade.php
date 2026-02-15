@php
    $isPro = auth()->user()?->isPro() ?? false;
    $dashboardRoute = ($isPro && Route::has('core.dashboard')) ? route('core.dashboard') : route('paneluser.index');
@endphp
<x-paneluser::layouts.master :title="'Categorias'">
    <div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500 pb-8">
        {{-- Hero CBAV --}}
        <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-indigo-600/5 dark:bg-indigo-600/10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-violet-600/5 dark:bg-violet-600/10 rounded-full blur-[100px]"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <nav class="flex items-center gap-2 text-xs font-bold text-indigo-600 dark:text-indigo-500 uppercase tracking-widest mb-4">
                        <span>Organização</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                        <span class="text-gray-400 dark:text-gray-500">Categorias</span>
                    </nav>
                    <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Minhas <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-600 dark:from-indigo-400 dark:to-violet-400">Categorias</span></h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">Classifique receitas e despesas no Extrato, nos orçamentos e nos relatórios. O sistema já traz categorias padrão; assinantes Pro podem criar personalizadas.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    @if($isPro && !($inspectionReadOnly ?? false))
                        <a href="{{ route('core.categories.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-indigo-500/20">
                            <x-icon name="plus" style="solid" class="w-5 h-5" />
                            Nova categoria
                        </a>
                    @endif
                    <a href="{{ $dashboardRoute }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                        <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                        Voltar
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="rounded-2xl border border-emerald-200 dark:border-emerald-800/50 bg-emerald-50 dark:bg-emerald-900/10 p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <x-icon name="circle-check" style="solid" class="w-5 h-5" />
                    </div>
                    <span class="font-medium text-gray-800 dark:text-gray-200">{{ session('success') }}</span>
                </div>
                <button type="button" @click="show = false" class="p-2 rounded-lg hover:bg-emerald-500/20 text-gray-500 hover:text-gray-700 transition-colors">
                    <x-icon name="xmark" style="solid" class="w-5 h-5" />
                </button>
            </div>
        @endif
        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition class="rounded-2xl border border-red-200 dark:border-red-800/50 bg-red-50 dark:bg-red-900/10 p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-500/20 flex items-center justify-center text-red-600 dark:text-red-400">
                        <x-icon name="triangle-exclamation" style="solid" class="w-5 h-5" />
                    </div>
                    <span class="font-medium text-gray-800 dark:text-gray-200">{{ session('error') }}</span>
                </div>
                <button type="button" @click="show = false" class="p-2 rounded-lg hover:bg-red-500/20 text-gray-500 hover:text-gray-700 transition-colors">
                    <x-icon name="xmark" style="solid" class="w-5 h-5" />
                </button>
            </div>
        @endif

        {{-- Dica: Como funcionam as categorias --}}
        <div class="rounded-3xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 p-6 shadow-sm">
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0">
                    <x-icon name="circle-info" style="duotone" class="w-5 h-5" />
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-1">Como funcionam as categorias no Vertex Contas</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">As categorias servem para <strong>classificar</strong> cada transação (receita ou despesa) no <a href="{{ route('core.transactions.index') }}" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline">Extrato</a>, definir <strong>orçamentos</strong> por categoria e ver relatórios por tipo de gasto. O sistema inclui categorias padrão (ex.: Alimentação, Transporte). Com <strong>Vertex Pro</strong> você pode criar categorias personalizadas com ícone e cor para organizar do seu jeito.</p>
                </div>
            </div>
        </div>

        <x-core::limit-status entity="category" label="Categorias personalizadas" />

        {{-- Receitas --}}
        @if($categories->has('income'))
            <div class="rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-white/5 flex items-center gap-3 bg-emerald-500/5 dark:bg-emerald-500/10">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <x-icon name="arrow-trend-up" style="duotone" class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-900 dark:text-white">Receitas</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Categorias para entradas</p>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($categories['income'] as $category)
                        <div class="group relative rounded-2xl p-5 border border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-gray-950/50 hover:border-indigo-500/30 hover:shadow-md transition-all">
                            @if(is_null($category->user_id))
                                <div class="absolute top-3 right-3 text-gray-400 dark:text-gray-500" title="Categoria padrão do sistema">
                                    <x-icon name="lock" style="solid" class="w-3.5 h-3.5" />
                                </div>
                            @endif
                            <div class="flex flex-col items-center text-center">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3 transition-transform group-hover:scale-110" style="background-color: {{ $category->color }}20; color: {{ $category->color }};">
                                    <x-icon name="{{ $category->icon }}" style="duotone" class="w-6 h-6" />
                                </div>
                                <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $category->name }}</p>
                                @if(!is_null($category->user_id))
                                    @if(!($inspectionReadOnly ?? false))
                                        <form action="{{ route('core.categories.destroy', $category) }}" method="POST" class="mt-2 opacity-0 group-hover:opacity-100 transition-opacity" onsubmit="return confirm('Excluir esta categoria?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 text-xs text-red-600 dark:text-red-400 hover:text-red-700 font-medium">
                                                <x-icon name="trash-can" style="solid" class="w-3 h-3" />
                                                Excluir
                                            </button>
                                        </form>
                                    @else
                                        <span class="mt-2 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Personalizada</span>
                                    @endif
                                @else
                                    <span class="mt-2 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Padrão</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Despesas --}}
        @if($categories->has('expense'))
            <div class="rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-white/5 flex items-center gap-3 bg-rose-500/5 dark:bg-rose-500/10">
                    <div class="w-10 h-10 rounded-xl bg-rose-600/10 dark:bg-rose-500/20 flex items-center justify-center text-rose-600 dark:text-rose-400">
                        <x-icon name="arrow-trend-down" style="duotone" class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-900 dark:text-white">Despesas</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Categorias para saídas</p>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($categories['expense'] as $category)
                        <div class="group relative rounded-2xl p-5 border border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-gray-950/50 hover:border-indigo-500/30 hover:shadow-md transition-all">
                            @if(is_null($category->user_id))
                                <div class="absolute top-3 right-3 text-gray-400 dark:text-gray-500" title="Categoria padrão do sistema">
                                    <x-icon name="lock" style="solid" class="w-3.5 h-3.5" />
                                </div>
                            @endif
                            <div class="flex flex-col items-center text-center">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3 transition-transform group-hover:scale-110" style="background-color: {{ $category->color }}20; color: {{ $category->color }};">
                                    <x-icon name="{{ $category->icon }}" style="duotone" class="w-6 h-6" />
                                </div>
                                <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $category->name }}</p>
                                @if(!is_null($category->user_id))
                                    @if(!($inspectionReadOnly ?? false))
                                        <form action="{{ route('core.categories.destroy', $category) }}" method="POST" class="mt-2 opacity-0 group-hover:opacity-100 transition-opacity" onsubmit="return confirm('Excluir esta categoria?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 text-xs text-red-600 dark:text-red-400 hover:text-red-700 font-medium">
                                                <x-icon name="trash-can" style="solid" class="w-3 h-3" />
                                                Excluir
                                            </button>
                                        </form>
                                    @else
                                        <span class="mt-2 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Personalizada</span>
                                    @endif
                                @else
                                    <span class="mt-2 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Padrão</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($categories->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 text-center rounded-3xl border-2 border-dashed border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-gray-950/50">
                <div class="w-24 h-24 rounded-full bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 dark:text-gray-700 mb-6 shadow-sm border border-gray-100 dark:border-white/5">
                    <x-icon name="tags" style="duotone" class="w-12 h-12 opacity-40 dark:opacity-20" />
                </div>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 leading-tight">Nenhuma categoria</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto">O sistema deve exibir categorias padrão. Se não aparecer nenhuma, entre em contato com o suporte.</p>
            </div>
        @endif

        {{-- CTA Pro: categorias personalizadas (só para não-Pro) --}}
        @if(!$isPro)
            <div class="rounded-3xl border-2 border-dashed border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-gray-950/50 p-8 flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <x-icon name="lock" style="solid" class="w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Categorias personalizadas (Vertex Pro)</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Crie categorias com ícone e cor para organizar suas finanças do seu jeito.</p>
                    </div>
                </div>
                <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold transition-colors">
                    <x-icon name="sparkles" style="duotone" class="w-4 h-4" />
                    Vertex Pro
                </a>
            </div>
        @endif
    </div>
</x-paneluser::layouts.master>
