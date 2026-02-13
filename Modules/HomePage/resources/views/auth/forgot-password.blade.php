<x-homepage::layouts.master>
    <x-homepage::layouts.navbar />

    <main class="min-h-screen bg-slate-50 dark:bg-slate-900 flex items-center justify-center p-4 pt-32 pb-20 font-['Poppins']">
        <!-- Background Decorations -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
            <div class="absolute top-1/2 left-1/2 w-96 h-96 bg-primary/10 rounded-full blur-3xl animate-pulse"></div>
        </div>

        <div class="w-full max-w-md">
            <!-- Glass Card -->
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-[40px] p-8 lg:p-12 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.1)] border border-white dark:border-slate-700">

                <div class="text-center mb-10">
                    <div class="mb-6 flex justify-center">
                        <x-logo type="full" size="text-3xl" />
                    </div>
                    <h2 class="text-3xl font-black text-slate-800 dark:text-white mb-2 tracking-tight">Recuperar senha</h2>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">Enviaremos um link de recuperação local</p>
                </div>

                <!-- Info Box -->
                <div class="mb-8 p-4 bg-primary/10 border border-primary/20 rounded-2xl flex items-start gap-3">
                    <x-icon name="circle-info" class="text-primary text-xl mt-0.5" />
                    <p class="text-sm text-slate-600 dark:text-slate-300 font-medium leading-relaxed">
                        Esqueceu sua senha? Sem problemas. Basta nos informar seu endereço de e-mail e enviaremos um link que permitirá escolher uma nova.
                    </p>
                </div>

                <!-- Error/Success Messages -->
                @if(session('status'))
                    <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/30 rounded-2xl flex items-center gap-3 animate-fade-in">
                        <x-icon name="circle-check" class="text-emerald-500 text-xl" />
                        <span class="text-sm text-emerald-600 dark:text-emerald-400 font-bold">{{ session('status') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800/30 rounded-2xl flex items-center gap-3 animate-shake">
                        <x-icon name="circle-exclamation" class="text-red-500 text-xl" />
                        <span class="text-sm text-red-600 dark:text-red-400 font-bold">{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">E-mail Cadastrado</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <x-icon name="envelope" />
                            </div>
                            <input type="email" name="email" id="email"
                                class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 border-none rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium"
                                placeholder="seu@email.com" required autofocus>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white px-8 py-4 rounded-2xl text-lg font-black shadow-xl shadow-primary/30 transform hover:-translate-y-1 active:scale-95 transition-all flex items-center justify-center gap-3">
                        Enviar Link
                        <x-icon name="paper-plane" />
                    </button>
                </form>

                <div class="mt-10 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-black text-primary hover:underline underline-offset-4 decoration-2 transition-all">
                        <x-icon name="arrow-left" />
                        Voltar para Login
                    </a>
                </div>
            </div>

            <!-- Footer Small -->
            <p class="mt-8 text-center text-xs text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest">
                &copy; {{ date('Y') }} VertexContas &bull; 100% Local &bull; Seguro
            </p>
        </div>
    </main>
</x-homepage::layouts.master>
