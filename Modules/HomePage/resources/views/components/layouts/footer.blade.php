<footer class="bg-slate-50 dark:bg-slate-950/50 pt-20 pb-10 border-t border-slate-100 dark:border-slate-900 font-['Poppins']">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
            <!-- Brand -->
            <div class="col-span-1 md:col-span-1">
                <div class="mb-6">
                    <x-logo type="full" size="text-xl" />
                </div>
                <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-6">
                    Tome o controle total da sua vida financeira com a plataforma mais completa de gestão pessoal 100% local e segura.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-primary hover:text-white transition-all">
                        <x-icon name="facebook" style="brands" />
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-primary hover:text-white transition-all">
                        <x-icon name="instagram" style="brands" />
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-primary hover:text-white transition-all">
                        <x-icon name="linkedin" style="brands" />
                    </a>
                </div>
            </div>

            <!-- Links -->
            <!-- Product -->
            <div class="col-span-1">
                <h4 class="text-slate-800 dark:text-white font-bold mb-6 text-sm uppercase tracking-widest">Produto</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('features.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-primary transition-colors text-sm font-medium">Funcionalidades</a></li>
                    <li><a href="{{ route('features.goals') }}" class="text-slate-500 dark:text-slate-400 hover:text-primary transition-colors text-sm font-medium">Metas e Objetivos</a></li>
                    <li><a href="{{ route('features.reports') }}" class="text-slate-500 dark:text-slate-400 hover:text-primary transition-colors text-sm font-medium">Relatórios</a></li>
                </ul>
            </div>

            <!-- Suporte -->
            <div class="col-span-1">
                <h4 class="text-slate-800 dark:text-white font-bold mb-6 text-sm uppercase tracking-widest">Suporte</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('faq') }}" class="text-slate-500 dark:text-slate-400 hover:text-primary transition-colors text-sm font-medium">Dúvidas Frequentes</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-slate-500 dark:text-slate-400 hover:text-primary transition-colors text-sm font-medium">Privacidade</a></li>
                    <li><a href="{{ route('terms') }}" class="text-slate-500 dark:text-slate-400 hover:text-primary transition-colors text-sm font-medium">Termos de Uso</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-span-1">
                <h4 class="text-slate-800 dark:text-white font-bold mb-6 text-sm uppercase tracking-widest">Contato</h4>
                <ul class="space-y-4">
                    <li class="flex items-center gap-3 text-sm text-slate-500 dark:text-slate-400">
                        <x-icon name="envelope" style="duotone" class="text-primary" />
                        suporte@vertexcontas.com
                    </li>
                    <li class="flex items-center gap-3 text-sm text-slate-500 dark:text-slate-400">
                        <x-icon name="location-dot" style="duotone" class="text-primary" />
                        Vertex Solutions LTDA, Brasil
                    </li>
                </ul>
            </div>
        </div>

        <div class="pt-8 border-t border-slate-200 dark:border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex flex-col md:flex-row items-center gap-4">
                <p class="text-slate-400 dark:text-slate-500 text-xs text-center md:text-left">
                    &copy; {{ date('Y') }} VertexContas. Todos os direitos reservados.
                </p>
                @auth
                    <div class="hidden md:flex items-center gap-2 px-3 py-1 bg-slate-100 dark:bg-slate-900 rounded-full border border-slate-200 dark:border-slate-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Conectado como <span class="text-primary">{{ Auth::user()->first_name }}</span></span>
                    </div>
                @endauth
            </div>
            <p class="text-slate-400 dark:text-slate-500 text-xs flex items-center gap-1">
                Desenvolvido com <x-icon name="heart" style="duotone" class="text-red-500 animate-pulse" /> por Vertex Solutions
            </p>
        </div>
    </div>
</footer>
