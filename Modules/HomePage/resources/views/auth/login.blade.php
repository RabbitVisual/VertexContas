<x-homepage::layouts.master>
    <x-homepage::layouts.navbar />

    <main class="min-h-screen bg-slate-50 dark:bg-slate-900 flex items-center justify-center p-4 pt-32 pb-20 font-['Poppins']">
        <!-- Background Decorations -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-primary/5 rounded-full blur-3xl animate-pulse delay-1000"></div>
        </div>

        <div class="w-full max-w-md" x-data="{ loginType: 'email' }">
            <!-- Glass Card -->
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-[40px] p-8 lg:p-12 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.1)] border border-white dark:border-slate-700">

                <div class="text-center mb-10">
                    <div class="mb-6 flex justify-center">
                        <x-logo type="full" size="text-3xl" />
                    </div>
                    <h2 class="text-3xl font-black text-slate-800 dark:text-white mb-2 tracking-tight">Login</h2>
                </div>

                <!-- Custom Tabs -->
                <div class="flex p-1.5 bg-slate-100 dark:bg-slate-900 rounded-2xl mb-8 relative">
                    <button @click="loginType = 'email'"
                        :class="loginType === 'email' ? 'bg-white dark:bg-slate-800 shadow-sm text-primary' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200'"
                        class="flex-1 py-3 text-sm font-black rounded-xl transition-all relative z-10 flex items-center justify-center gap-2">
                        <x-icon name="envelope" class="text-lg" />
                        E-mail
                    </button>
                    <button @click="loginType = 'cpf'"
                        :class="loginType === 'cpf' ? 'bg-white dark:bg-slate-800 shadow-sm text-primary' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200'"
                        class="flex-1 py-3 text-sm font-black rounded-xl transition-all relative z-10 flex items-center justify-center gap-2">
                        <x-icon name="id-card" class="text-lg" />
                        CPF
                    </button>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800/30 rounded-2xl flex items-center gap-3 animate-shake">
                        <x-icon name="circle-exclamation" class="text-red-500 text-xl" />
                        <span class="text-sm text-red-600 dark:text-red-400 font-bold">{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <input type="hidden" name="login_type" :value="loginType">

                    <!-- Email Field -->
                    <div class="space-y-2" x-show="loginType === 'email'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        <label for="email" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">E-mail</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <x-icon name="envelope" />
                            </div>
                            <input type="email" name="email" id="email"
                                class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 border-none rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium"
                                placeholder="seu@email.com" :required="loginType === 'email'">
                        </div>
                    </div>

                    <!-- CPF Field -->
                    <div class="space-y-2" x-show="loginType === 'cpf'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        <label for="cpf" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">CPF</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <x-icon name="id-card" />
                            </div>
                            <input type="text" name="cpf" id="cpf"
                                class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 border-none rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium"
                                placeholder="000.000.000-00" :required="loginType === 'cpf'" x-mask="'cpf'">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center ml-1">
                            <label for="password" class="block text-sm font-bold text-slate-700 dark:text-slate-300">Senha</label>
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-primary hover:text-primary-dark transition-colors">Esqueceu?</a>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <x-icon name="lock" />
                            </div>
                            <input type="password" name="password" id="password"
                                class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 border-none rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium"
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center gap-3 px-1">
                        <input type="checkbox" name="remember" id="remember"
                            class="w-5 h-5 rounded-lg border-none bg-slate-200 dark:bg-slate-700 text-primary focus:ring-primary/30">
                        <label for="remember" class="text-sm font-bold text-slate-600 dark:text-slate-400 cursor-pointer select-none">Lembrar-me</label>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white px-8 py-4 rounded-2xl text-lg font-black shadow-xl shadow-primary/30 transform hover:-translate-y-1 active:scale-95 transition-all flex items-center justify-center gap-3">
                        Entrar Agora
                        <x-icon name="arrow-right-to-bracket" />
                    </button>

                    <!-- Auto Login Demo -->
                    <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                        <p class="text-xs font-bold text-center text-slate-400 uppercase tracking-widest mb-4">Acesso Rápido (Demo)</p>
                        <div class="grid grid-cols-3 gap-3">
                            <button type="button" @click="loginType = 'email'; document.getElementById('email').value = 'admin@vertexcontas.com'; document.getElementById('password').value = 'password'; $el.closest('form').submit()"
                                class="flex flex-col items-center justify-center p-3 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 rounded-xl transition-colors border border-red-100 dark:border-red-800/30 group">
                                <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-800 flex items-center justify-center text-red-600 dark:text-red-200 mb-2 group-hover:scale-110 transition-transform">
                                    <x-icon name="user-shield" class="text-sm" />
                                </div>
                                <span class="text-xs font-bold text-red-700 dark:text-red-300">Admin</span>
                            </button>

                            <button type="button" @click="loginType = 'email'; document.getElementById('email').value = 'user@vertexcontas.com'; document.getElementById('password').value = 'password'; $el.closest('form').submit()"
                                class="flex flex-col items-center justify-center p-3 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-xl transition-colors border border-blue-100 dark:border-blue-800/30 group">
                                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center text-blue-600 dark:text-blue-200 mb-2 group-hover:scale-110 transition-transform">
                                    <x-icon name="user" class="text-sm" />
                                </div>
                                <span class="text-xs font-bold text-blue-700 dark:text-blue-300">User</span>
                            </button>

                            <button type="button" @click="loginType = 'email'; document.getElementById('email').value = 'support@vertexcontas.com'; document.getElementById('password').value = 'password'; $el.closest('form').submit()"
                                class="flex flex-col items-center justify-center p-3 bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/40 rounded-xl transition-colors border border-purple-100 dark:border-purple-800/30 group">
                                <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-800 flex items-center justify-center text-purple-600 dark:text-purple-200 mb-2 group-hover:scale-110 transition-transform">
                                    <x-icon name="headset" class="text-sm" />
                                </div>
                                <span class="text-xs font-bold text-purple-700 dark:text-purple-300">Support</span>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="mt-10 text-center">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Não tem uma conta?
                        <a href="{{ route('register') }}" class="text-primary font-black hover:underline underline-offset-4 decoration-2 transition-all">Criar Conta Grátis</a>
                    </p>
                </div>
            </div>

            <!-- Footer Small -->
            <p class="mt-8 text-center text-xs text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest">
                &copy; {{ date('Y') }} VertexContas &bull; 100% Local &bull; Seguro
            </p>
        </div>
    </main>
</x-homepage::layouts.master>
