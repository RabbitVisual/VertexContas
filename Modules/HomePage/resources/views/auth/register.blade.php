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
                        <x-logo type="full" context="homepage" size="text-3xl" />
                    </div>
                    <h2 class="text-3xl font-black text-slate-800 dark:text-white mb-2 tracking-tight">Crie sua conta</h2>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">Inicie sua jornada financeira local hoje</p>
                </div>

                <!-- Error Messages (sempre amigáveis; nunca exibir chaves de tradução) -->
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl flex items-start gap-3" role="alert" aria-live="polite">
                        <x-icon name="circle-exclamation" class="text-red-500 dark:text-red-400 text-xl shrink-0 mt-0.5" />
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-red-800 dark:text-red-200 mb-2">Corrija os erros abaixo antes de continuar:</p>
                            <ul class="text-sm text-red-700 dark:text-red-300 space-y-1 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    @php
                                        $translated = __($error);
                                        $msg = ($translated !== $error) ? $translated : $error;
                                        if (\Illuminate\Support\Str::contains($msg, 'validation.') || \Illuminate\Support\Str::contains($msg, 'password.')) {
                                            $msg = 'Verifique os dados e as regras de senha (letras, números e caractere especial).';
                                        }
                                    @endphp
                                    <li>{{ $msg }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-6" @if($recaptchaEnabled ?? false) id="register-form" @endif>
                    @csrf
                    @if($recaptchaEnabled ?? false)
                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div class="space-y-2">
                            <label for="first_name" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">Nome</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <x-icon name="user" />
                                </div>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}"
                                    class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium {{ $errors->has('first_name') ? 'ring-2 ring-red-500 dark:ring-red-500 border-2 border-red-500 dark:border-red-500' : 'border-none' }}"
                                    placeholder="João" required autofocus aria-invalid="{{ $errors->has('first_name') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('first_name') ? 'first_name_error' : '' }}">
                            </div>
                            @error('first_name')
                                <p id="first_name_error" class="text-sm text-red-600 dark:text-red-400 font-medium" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="space-y-2">
                            <label for="last_name" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">Sobrenome</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <x-icon name="user" />
                                </div>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                                    class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium {{ $errors->has('last_name') ? 'ring-2 ring-red-500 dark:ring-red-500 border-2 border-red-500 dark:border-red-500' : 'border-none' }}"
                                    placeholder="Silva" required aria-invalid="{{ $errors->has('last_name') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('last_name') ? 'last_name_error' : '' }}">
                            </div>
                            @error('last_name')
                                <p id="last_name_error" class="text-sm text-red-600 dark:text-red-400 font-medium" role="alert">{{ $message }}</p>
                            @enderror
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
                                    class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium {{ $errors->has('email') ? 'ring-2 ring-red-500 dark:ring-red-500 border-2 border-red-500 dark:border-red-500' : 'border-none' }}"
                                    placeholder="joao@exemplo.com" required aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('email') ? 'email_error' : '' }}">
                            </div>
                            @error('email')
                                <p id="email_error" class="text-sm text-red-600 dark:text-red-400 font-medium" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CPF -->
                        <div class="space-y-2">
                            <label for="cpf" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">CPF</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <x-icon name="id-card" />
                                </div>
                                <input type="text" name="cpf" id="cpf" value="{{ old('cpf') }}"
                                    class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium {{ $errors->has('cpf') ? 'ring-2 ring-red-500 dark:ring-red-500 border-2 border-red-500 dark:border-red-500' : 'border-none' }}"
                                    placeholder="000.000.000-00" x-mask="'cpf'" aria-invalid="{{ $errors->has('cpf') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('cpf') ? 'cpf_error' : '' }}">
                            </div>
                            @error('cpf')
                                <p id="cpf_error" class="text-sm text-red-600 dark:text-red-400 font-medium" role="alert">{{ $message }}</p>
                            @enderror
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
                                <input type="text" name="birth_date" id="birth_date" value="{{ old('birth_date') }}"
                                    x-mask="'date'" placeholder="dd/mm/aaaa"
                                    class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/50 transition-all font-medium {{ $errors->has('birth_date') ? 'ring-2 ring-red-500 dark:ring-red-500 border-2 border-red-500 dark:border-red-500' : 'border-none' }}"
                                    aria-invalid="{{ $errors->has('birth_date') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('birth_date') ? 'birth_date_error' : '' }}">
                            </div>
                            @error('birth_date')
                                <p id="birth_date_error" class="text-sm text-red-600 dark:text-red-400 font-medium" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">Telefone</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <x-icon name="phone" />
                                </div>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                    class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium {{ $errors->has('phone') ? 'ring-2 ring-red-500 dark:ring-red-500 border-2 border-red-500 dark:border-red-500' : 'border-none' }}"
                                    placeholder="(00) 00000-0000" x-mask="'phone'" aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('phone') ? 'phone_error' : '' }}">
                            </div>
                            @error('phone')
                                <p id="phone_error" class="text-sm text-red-600 dark:text-red-400 font-medium" role="alert">{{ $message }}</p>
                            @enderror
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
                                    class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium {{ $errors->has('password') ? 'ring-2 ring-red-500 dark:ring-red-500 border-2 border-red-500 dark:border-red-500' : 'border-none' }}"
                                    placeholder="••••••••" required aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('password') ? 'password_error' : '' }}">
                            </div>
                            @error('password')
                                <p id="password_error" class="text-sm text-red-600 dark:text-red-400 font-medium" role="alert">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-slate-500 dark:text-slate-400">Mínimo 8 caracteres, com letras, números e um caractere especial.</p>
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <label for="password_confirmation" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">Confirmar Senha</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <x-icon name="lock-keyhole" />
                                </div>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="block w-full pl-12 pr-5 py-4 bg-slate-100 dark:bg-slate-900 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary/50 transition-all font-medium {{ $errors->has('password_confirmation') ? 'ring-2 ring-red-500 dark:ring-red-500 border-2 border-red-500 dark:border-red-500' : 'border-none' }}"
                                    placeholder="••••••••" required aria-invalid="{{ $errors->has('password_confirmation') ? 'true' : 'false' }}" aria-describedby="{{ $errors->has('password_confirmation') ? 'password_confirmation_error' : '' }}">
                            </div>
                            @error('password_confirmation')
                                <p id="password_confirmation_error" class="text-sm text-red-600 dark:text-red-400 font-medium" role="alert">{{ $message }}</p>
                            @enderror
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

                @if($recaptchaEnabled ?? false)
                    <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}" async defer></script>
                    <script>
                        document.getElementById('register-form')?.addEventListener('submit', function(e) {
                            e.preventDefault();
                            const form = this;
                            const input = document.getElementById('g-recaptcha-response');
                            const btn = form.querySelector('button[type="submit"]');
                            if (!input || typeof grecaptcha === 'undefined') {
                                form.submit();
                                return;
                            }
                            if (btn) { btn.disabled = true; btn.innerHTML = 'Aguarde...'; }
                            input.value = '';
                            grecaptcha.ready(function() {
                                grecaptcha.execute('{{ $recaptchaSiteKey }}', { action: 'register' })
                                    .then(function(token) {
                                        input.value = token;
                                        form.submit();
                                    })
                                    .catch(function() {
                                        if (btn) { btn.disabled = false; btn.innerHTML = 'Criar Minha Conta <i class="fa-solid fa-user-plus"></i>'; }
                                        alert('Não foi possível verificar a segurança. Desative bloqueadores de anúncios ou tente novamente.');
                                    });
                            });
                        });
                    </script>
                @endif

                <div class="mt-10 text-center">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Já tem uma conta?
                        <a href="{{ route('login') }}" class="text-primary font-black hover:underline underline-offset-4 decoration-2 transition-all">Fazer Login</a>
                    </p>
                </div>
            </div>

            <!-- Footer Small -->
            <p class="mt-8 text-center text-xs text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest">
                &copy; {{ date('Y') }} {{ config('app.name') }} &bull; 100% Local &bull; Seguro
            </p>
        </div>
    </main>
</x-homepage::layouts.master>
