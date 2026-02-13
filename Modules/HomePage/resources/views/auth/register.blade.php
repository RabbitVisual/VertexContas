<x-homepage::layouts.master>
    <x-homepage::layouts.navbar />

    <main class="min-h-screen bg-slate-50 dark:bg-slate-900 flex items-center justify-center p-4 pt-32 pb-20 font-['Poppins']">
        <!-- Background Decorations -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
            <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-primary/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/4 left-1/4 w-96 h-96 bg-primary/5 rounded-full blur-3xl animate-pulse delay-1000"></div>
        </div>

        <div class="w-full max-w-2xl">
            <!-- Glass Card -->
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-[40px] p-8 lg:p-12 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.1)] border border-white dark:border-slate-700">

                <div class="text-center mb-10">
                    <div class="mb-6 flex justify-center">
                        <x-logo type="full" size="text-3xl" />
                    </div>
                    <h2 class="text-3xl font-black text-slate-800 dark:text-white mb-2 tracking-tight">Crie sua conta</h2>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">Inicie sua jornada financeira local hoje</p>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800/30 rounded-2xl flex items-center gap-3 animate-shake">
                        <x-icon name="circle-exclamation" class="text-red-500 text-xl" />
                        <ul class="text-sm text-red-600 dark:text-red-400 font-bold list-none">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div class="space-y-2">
                            <label for="first_name" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">Nome</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <x-icon name="user" />
                                </div>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}"
                                    class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 border-none rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium"
                                    placeholder="João" required autofocus>
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div class="space-y-2">
                            <label for="last_name" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">Sobrenome</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <x-icon name="user" />
                                </div>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                                    class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 border-none rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium"
                                    placeholder="Silva" required>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">E-mail</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <x-icon name="envelope" />
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 border-none rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium"
                                    placeholder="joao@exemplo.com" required>
                            </div>
                        </div>

                        <!-- CPF -->
                        <div class="space-y-2">
                            <label for="cpf" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">CPF</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <x-icon name="id-card" />
                                </div>
                                <input type="text" name="cpf" id="cpf" value="{{ old('cpf') }}"
                                    class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 border-none rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium"
                                    placeholder="000.000.000-00" x-mask="'cpf'">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Birth Date -->
                        <div class="space-y-2">
                            <label for="birth_date" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">Data de Nascimento</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <x-icon name="calendar" />
                                </div>
                                <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}"
                                    class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 border-none rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/50 transition-all font-medium">
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">Telefone</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <x-icon name="phone" />
                                </div>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                    class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 border-none rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium"
                                    placeholder="(00) 00000-0000" x-mask="'phone'">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">Senha</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <x-icon name="lock" />
                                </div>
                                <input type="password" name="password" id="password"
                                    class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 border-none rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium"
                                    placeholder="••••••••" required>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <label for="password_confirmation" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">Confirmar Senha</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <x-icon name="lock-keyhole" />
                                </div>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 border-none rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium"
                                    placeholder="••••••••" required>
                            </div>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="flex items-center gap-3 px-1">
                        <input type="checkbox" name="terms" id="terms" required
                            class="w-5 h-5 rounded-lg border-none bg-slate-200 dark:bg-slate-700 text-primary focus:ring-primary/30">
                        <label for="terms" class="text-sm font-bold text-slate-600 dark:text-slate-400 cursor-pointer select-none">
                            Eu aceito os <a href="{{ route('terms') }}" class="text-primary hover:underline">Termos de Uso</a> e <a href="{{ route('privacy') }}" class="text-primary hover:underline">Privacidade</a>.
                        </label>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white px-8 py-4 rounded-2xl text-lg font-black shadow-xl shadow-primary/30 transform hover:-translate-y-1 active:scale-95 transition-all flex items-center justify-center gap-3">
                        Criar Minha Conta
                        <x-icon name="user-plus" />
                    </button>
                </form>

                <div class="mt-10 text-center">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Já tem uma conta?
                        <a href="{{ route('login') }}" class="text-primary font-black hover:underline underline-offset-4 decoration-2 transition-all">Fazer Login</a>
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
