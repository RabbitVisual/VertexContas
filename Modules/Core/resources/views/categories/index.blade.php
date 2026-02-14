<x-paneluser::layouts.master :title="'Categorias'">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-slate-800 dark:text-white">
                <x-icon name="tags" style="solid" class="text-primary" /> Categorias
            </h2>
            <a href="{{ route('core.categories.create') }}"
               class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-full text-sm font-bold shadow-lg shadow-primary/25 transform hover:-translate-y-0.5 transition-all">
                <x-icon name="plus" style="solid" /> Nova Categoria
            </a>
        </div>
    </div>

    <div class="py-12 font-['Poppins']">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-lg mb-6">
                    <x-icon name="check-circle" style="solid" /> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
                    <x-icon name="exclamation-circle" style="solid" /> {{ session('error') }}
                </div>
            @endif

            <!-- Limit Status Bar -->
            <x-core::limit-status entity="category" label="Categorias Personalizadas" />

            {{-- Income Categories --}}
            @if($categories->has('income'))
                <div class="mb-10">
                    <h3 class="flex items-center text-2xl font-black text-slate-800 dark:text-white mb-6">
                        <div class="bg-emerald-100 dark:bg-emerald-900/30 p-2 rounded-lg mr-3">
                             <x-icon name="arrow-up" style="solid" class="text-emerald-600 dark:text-emerald-400" />
                        </div>
                        Receitas
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                        @foreach($categories['income'] as $category)
                            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 hover:shadow-xl hover:-translate-y-1 transition-all group relative">

                                @if(is_null($category->user_id))
                                    <div class="absolute top-3 right-3 text-slate-300 dark:text-slate-600" title="Categoria Padrão">
                                        <x-icon name="lock" style="solid" class="h-3 w-3" />
                                    </div>
                                @endif

                                <div class="flex flex-col items-center text-center">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110 shadow-sm" style="background-color: {{ $category->color }}15; color: {{ $category->color }};">
                                        <x-icon name="{{ $category->icon }}" style="duotone" class="text-2xl" />
                                    </div>
                                    <h4 class="font-bold text-slate-700 dark:text-slate-200 text-sm mb-1">{{ $category->name }}</h4>

                                    @if(!is_null($category->user_id)) {{-- Only show delete for user-created categories --}}
                                        <form action="{{ route('core.categories.destroy', $category) }}" method="POST" class="mt-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Excluir categoria?')" class="text-xs text-red-500 hover:text-red-700 flex items-center justify-center bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded-md">
                                                <x-icon name="trash" style="solid" class="h-3 w-3 mr-1" /> Excluir
                                            </button>
                                        </form>
                                    @else
                                        <span class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider opacity-0 group-hover:opacity-100 transition-opacity">Padrão</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Expense Categories --}}
            @if($categories->has('expense'))
                <div class="mb-10">
                    <h3 class="flex items-center text-2xl font-black text-slate-800 dark:text-white mb-6">
                        <div class="bg-red-100 dark:bg-red-900/30 p-2 rounded-lg mr-3">
                             <x-icon name="arrow-down" style="solid" class="text-red-600 dark:text-red-400" />
                        </div>
                        Despesas
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                        @foreach($categories['expense'] as $category)
                            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 hover:shadow-xl hover:-translate-y-1 transition-all group relative">

                                @if(is_null($category->user_id))
                                    <div class="absolute top-3 right-3 text-slate-300 dark:text-slate-600" title="Categoria Padrão">
                                        <x-icon name="lock" style="solid" class="h-3 w-3" />
                                    </div>
                                @endif

                                <div class="flex flex-col items-center text-center">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110 shadow-sm" style="background-color: {{ $category->color }}15; color: {{ $category->color }};">
                                        <x-icon name="{{ $category->icon }}" style="duotone" class="text-2xl" />
                                    </div>
                                    <h4 class="font-bold text-slate-700 dark:text-slate-200 text-sm mb-1">{{ $category->name }}</h4>

                                    @if(!is_null($category->user_id)) {{-- Only show delete for user-created categories --}}
                                        <form action="{{ route('core.categories.destroy', $category) }}" method="POST" class="mt-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Excluir categoria?')" class="text-xs text-red-500 hover:text-red-700 flex items-center justify-center bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded-md">
                                                <x-icon name="trash" style="solid" class="h-3 w-3 mr-1" /> Excluir
                                            </button>
                                        </form>
                                    @else
                                        <span class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider opacity-0 group-hover:opacity-100 transition-opacity">Padrão</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($categories->isEmpty())
                <!-- This shouldn't happen now with seeded categories, but keeping as fallback -->
                 <div class="text-center py-16">
                    <x-icon name="tags" style="solid" size="8xl" class="text-slate-300 dark:text-slate-600 mb-6" />
                    <h3 class="text-2xl font-black text-slate-600 dark:text-slate-400 mb-4">Nenhuma categoria cadastrada</h3>
                 </div>
            @endif

            @if(!auth()->user()->hasRole('pro_user') && !auth()->user()->hasRole('admin'))
                <!-- Pro Upsell Banner -->
                <div class="relative overflow-hidden bg-slate-900 rounded-3xl p-8 shadow-2xl mt-12 mb-8">
                    <!-- Background Effects -->
                    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-purple-600/20 blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 rounded-full bg-indigo-600/20 blur-3xl"></div>

                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                        <div>
                            <span class="bg-gradient-to-r from-purple-400 to-pink-400 text-transparent bg-clip-text font-black text-sm uppercase tracking-widest mb-2 block">Premium</span>
                            <h3 class="text-3xl font-black text-white mb-2">Desbloqueie Categorias Ilimitadas</h3>
                            <p class="text-slate-400 max-w-xl">
                                Crie categorias personalizadas com ícones e cores exclusivas para organizar suas finanças exatamente do seu jeito.
                            </p>
                        </div>
                        <a href="{{ route('user.subscription.index') }}" class="whitespace-nowrap inline-flex items-center px-8 py-4 bg-white text-slate-900 font-bold rounded-xl hover:bg-slate-50 transition-all transform hover:scale-105 shadow-lg">
                            <x-icon name="crown" class="text-purple-600 mr-2" />
                            Seja Vertex Pro
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-paneluser::layouts.master>
