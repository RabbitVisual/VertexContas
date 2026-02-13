<x-homepage::layouts.master>
    <x-homepage::layouts.navbar />

    <main class="min-h-screen bg-slate-50 dark:bg-slate-900 flex items-center justify-center p-4 pt-32 pb-20 font-['Poppins']">
        <!-- Background Decorations -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
            <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-primary/10 rounded-full blur-3xl animate-pulse"></div>
        </div>

        <div class="w-full max-w-md">
            <!-- Glass Card -->
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-[40px] p-8 lg:p-12 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.1)] border border-white dark:border-slate-700">

                <div class="text-center mb-10">
                    <div class="mb-6 flex justify-center">
                        <x-logo type="full" size="text-3xl" />
                    </div>
                    <h2 class="text-3xl font-black text-slate-800 dark:text-white mb-2 tracking-tight">Nova senha</h2>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">Crie uma nova credencial segura</p>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800/30 rounded-2xl flex items-center gap-3 animate-shake">
                        <x-icon name="circle-exclamation" class="text-red-500 text-xl" />
                        <span class="text-sm text-red-600 dark:text-red-400 font-bold">{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">E-mail</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <x-icon name="envelope" />
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email', $email) }}"
                                class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 border-none rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium"
                                placeholder="seu@email.com" required readonly>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">Nova Senha</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <x-icon name="lock" />
                            </div>
                            <input type="password" name="password" id="password"
                                class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 border-none rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium"
                                placeholder="••••••••" required autofocus>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">Confirmar Nova Senha</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <x-icon name="lock-keyhole" />
                            </div>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 border-none rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium"
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white px-8 py-4 rounded-2xl text-lg font-black shadow-xl shadow-primary/30 transform hover:-translate-y-1 active:scale-95 transition-all flex items-center justify-center gap-3">
                        Redefinir Senha
                        <x-icon name="shield-keyhole" />
                    </button>
                </form>
            </div>

            <!-- Footer Small -->
            <p class="mt-8 text-center text-xs text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest">
                &copy; {{ date('Y') }} VertexContas &bull; 100% Local &bull; Seguro
            </p>
        </div>
    </main>
</x-homepage::layouts.master>
