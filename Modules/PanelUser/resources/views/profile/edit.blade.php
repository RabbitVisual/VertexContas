<x-paneluser::layouts.master :title="'Editar Perfil'">
    <div class="min-h-[calc(100vh-6rem)] bg-gray-50 dark:bg-slate-950 transition-colors duration-200 pb-24">
        <div class="max-w-7xl mx-auto px-6 pt-8">
            {{-- Dashboard Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
                <div>
                    <nav class="flex mb-2" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                            <li>Painel</li>
                            <li><x-icon name="chevron-right" style="solid" class="w-3 h-3" /></li>
                            <li class="hover:text-primary transition-colors cursor-pointer" onclick="window.location='{{ route('user.profile.show') }}'">Meu Perfil</li>
                            <li><x-icon name="chevron-right" style="solid" class="w-3 h-3" /></li>
                            <li class="text-primary">Editar Cadastro</li>
                        </ol>
                    </nav>
                    <div class="flex items-center gap-4 flex-wrap">
                        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Configurações de Perfil</h1>
                        @php
                            $fields = [$user->first_name, $user->last_name, $user->email, $user->cpf, $user->phone, $user->birth_date, $user->photo];
                            $filled = collect($fields)->filter(fn($v) => !empty($v))->count();
                            $profileCompletion = min(100, (int) round(($filled / 7) * 100));
                        @endphp
                        <div class="hidden lg:flex items-center gap-2 bg-primary/10 dark:bg-primary/20 px-3 py-1 rounded-full border border-primary/20 dark:border-primary/30">
                            <span class="text-[10px] font-black text-primary uppercase tracking-widest">Completude: {{ $profileCompletion }}%</span>
                            <div class="w-20 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-primary rounded-full transition-all duration-1000" style="width: {{ $profileCompletion }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('user.profile.show') }}"
                    class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white transition-colors group">
                    <x-icon name="chevron-left" style="solid" class="w-5 h-5 group-hover:-translate-x-1 transition-transform" />
                    Voltar para o Perfil
                </a>
            </div>

            <div class="flex flex-col lg:flex-row gap-8 items-start">

                {{-- Sticky Sidebar Navigation --}}
                <aside class="w-full lg:w-64 lg:min-w-[16rem] lg:sticky lg:top-24 space-y-4 self-start">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-2 shadow-sm">
                        @php
                            $navItems = [
                                ['id' => 'personal', 'label' => 'Dados Básicos', 'icon' => 'user'],
                                ['id' => 'contact', 'label' => 'Contato', 'icon' => 'phone'],
                                ['id' => 'photos', 'label' => 'Fotos de Perfil', 'icon' => 'images'],
                            ];
                        @endphp

                        @foreach($navItems as $item)
                            <button type="button"
                                onclick="scrollToSection('{{ $item['id'] }}')"
                                data-nav="{{ $item['id'] }}"
                                class="nav-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all border-l-4 border-transparent group text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800">
                                <x-icon name="{{ $item['icon'] }}" style="solid" class="w-5 h-5 transition-colors group-hover:text-primary" />
                                {{ $item['label'] }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Guided Help Card --}}
                    <div id="contextual-tip" class="hidden lg:block bg-primary dark:bg-primary/90 rounded-3xl p-6 text-white shadow-xl shadow-primary/20 transition-all duration-500 transform translate-y-2 opacity-0">
                        <div class="flex items-center gap-2 mb-3">
                            <x-icon name="lightbulb" style="solid" class="w-5 h-5 text-white/80" />
                            <h4 class="text-[10px] font-black uppercase tracking-widest text-white/80">Dica Vertex</h4>
                        </div>
                        <p id="tip-text" class="text-xs font-medium leading-relaxed opacity-90 italic">
                            Selecione uma seção para ver dicas úteis de preenchimento.
                        </p>
                    </div>
                </aside>

                {{-- Main Form Content --}}
                <div class="flex-1 w-full lg:max-w-4xl min-w-0">
                    <form id="profile-form" action="{{ route('user.profile.update') }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')

                        {{-- Section: Personal Info --}}
                        <section id="personal" class="scroll-mt-28 bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all hover:shadow-md">
                            <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-primary text-white rounded-xl shadow-lg shadow-primary/10"><x-icon name="user" style="solid" class="w-5 h-5" /></div>
                                    <h2 class="text-lg font-black text-gray-900 dark:text-white">Dados Básicos</h2>
                                </div>
                                <span class="text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">Campos Obrigatórios *</span>
                            </div>
                            <div class="p-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-1">
                                        <label for="first_name" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500">Nome *</label>
                                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" required
                                            class="modern-input w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 dark:bg-slate-800 text-gray-900 dark:text-white font-semibold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all">
                                        @error('first_name') <p class="text-[10px] text-red-500 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-1">
                                        <label for="last_name" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500">Sobrenome *</label>
                                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required
                                            class="modern-input w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 dark:bg-slate-800 text-gray-900 dark:text-white font-semibold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all">
                                        @error('last_name') <p class="text-[10px] text-red-500 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-1 md:col-span-2">
                                        <label for="birth_date" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500">Data de Nascimento</label>
                                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                                            class="modern-input w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 dark:bg-slate-800 text-gray-900 dark:text-white font-semibold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all">
                                        @error('birth_date') <p class="text-[10px] text-red-500 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- Section: Contact --}}
                        <section id="contact" class="scroll-mt-28 bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all hover:shadow-md">
                            <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-3">
                                <div class="p-2 bg-indigo-600 text-white rounded-xl shadow-lg shadow-indigo-500/10"><x-icon name="phone" style="solid" class="w-5 h-5" /></div>
                                <h2 class="text-lg font-black text-gray-900 dark:text-white">Meios de Contato</h2>
                            </div>
                            <div class="p-8 space-y-6">
                                <div class="space-y-1">
                                    <label for="phone" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500">Telefone</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                        class="modern-input w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 dark:bg-slate-800 text-gray-900 dark:text-white font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all">
                                    @error('phone') <p class="text-[10px] text-red-500 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                                </div>

                                {{-- E-mail (bloqueado) --}}
                                <div class="space-y-1">
                                    <label for="email" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500">
                                        E-mail <x-icon name="lock" style="solid" class="w-3 h-3 text-slate-400" />
                                    </label>
                                    <div class="relative">
                                        <input type="email" id="email" value="{{ $user->email }}"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800/50 text-gray-500 dark:text-slate-400 font-medium cursor-not-allowed" readonly disabled>
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2">
                                            <x-icon name="lock" style="solid" class="w-5 h-5 text-slate-400" />
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-gray-400 dark:text-slate-500 mt-1 italic">E-mail não pode ser alterado por você. Autorize o suporte em Segurança para alterações.</p>
                                </div>

                                {{-- CPF (bloqueado) --}}
                                <div class="space-y-1">
                                    <label for="cpf" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500">
                                        CPF <x-icon name="lock" style="solid" class="w-3 h-3 text-slate-400" />
                                    </label>
                                    <div class="relative">
                                        <input type="text" id="cpf" value="{{ $user->cpf ? preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $user->cpf) : 'Não informado' }}"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800/50 text-gray-500 dark:text-slate-400 font-mono font-medium cursor-not-allowed" readonly disabled>
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2">
                                            @if($user->cpf)
                                                <x-icon name="shield-check" style="solid" class="w-5 h-5 text-emerald-500" title="Verificado" />
                                            @else
                                                <x-icon name="lock" style="solid" class="w-5 h-5 text-slate-400" />
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-gray-400 dark:text-slate-500 mt-1 italic">CPF é protegido. Para alterar, autorize o acesso do suporte em Segurança.</p>
                                </div>
                            </div>
                        </section>

                        {{-- Section: Photos --}}
                        <section id="photos" class="scroll-mt-28 bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden pb-10 transition-all hover:shadow-md">
                            <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-slate-900 dark:bg-primary text-white rounded-xl"><x-icon name="shield-check" style="solid" class="w-5 h-5" /></div>
                                    <h2 class="text-lg font-black text-gray-900 dark:text-white">Fotos de Perfil</h2>
                                </div>
                                <span class="text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">{{ $user->photos->count() }}/3 fotos</span>
                            </div>

                            <div class="p-8 flex flex-col items-center justify-center">
                                {{-- Photo Selector Area (Instagram Style) --}}
                                <div class="flex flex-col items-center p-6 bg-gray-50/50 dark:bg-slate-800/50 rounded-3xl border-2 border-dashed border-gray-200 dark:border-slate-700 w-full">
                                    <div class="relative mb-6">
                                        <div class="w-28 h-28 rounded-full overflow-hidden border-4 border-white dark:border-slate-800 shadow-2xl relative">
                                            @if ($user->photo)
                                                <img id="main-preview" src="{{ asset('storage/'.$user->photo) }}" alt="Avatar" class="w-full h-full object-cover">
                                            @else
                                                <div id="main-placeholder" class="w-full h-full bg-primary/10 dark:bg-primary/20 flex items-center justify-center text-3xl font-black text-primary font-serif">
                                                    {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($user->last_name ?? '', 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        @if($user->photo)
                                            <div class="absolute -bottom-1 -right-1 bg-emerald-500 border-2 border-white dark:border-slate-800 rounded-full p-1.5 shadow-xl">
                                                <x-icon name="check" style="solid" class="w-3.5 h-3.5 text-white" />
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-3 p-2.5 bg-white dark:bg-slate-800 rounded-full shadow-xl shadow-gray-200/20 dark:shadow-none border border-gray-100 dark:border-slate-700 flex-wrap justify-center">
                                        @foreach($user->photos as $photo)
                                            <div class="relative group">
                                                <button type="button"
                                                    onclick="document.getElementById('activate-photo-{{ $photo->id }}').submit()"
                                                    class="w-12 h-12 rounded-full overflow-hidden border-2 {{ $user->photo === $photo->path ? 'border-primary ring-4 ring-primary/10' : 'border-gray-100 dark:border-slate-700 grayscale hover:grayscale-0' }} transition-all transform active:scale-95 shadow-sm">
                                                    <img src="{{ asset('storage/'.$photo->path) }}" alt="Foto" class="w-full h-full object-cover">
                                                </button>

                                                <button type="button"
                                                    onclick="if(confirm('Excluir esta foto?')) document.getElementById('delete-photo-{{ $photo->id }}').submit()"
                                                    class="absolute -top-1 -right-1 opacity-0 group-hover:opacity-100 transition-all scale-75 group-hover:scale-100 bg-rose-500 text-white rounded-full p-1 shadow-2xl hover:bg-rose-600 border border-white dark:border-slate-900">
                                                    <x-icon name="xmark" style="solid" class="w-3 h-3" />
                                                </button>
                                            </div>
                                        @endforeach

                                        @if($user->photos->count() < 3)
                                            <label for="photo-upload" class="w-12 h-12 rounded-full border-2 border-dashed border-gray-300 dark:border-slate-600 flex items-center justify-center hover:bg-primary/10 dark:hover:bg-primary/20 hover:border-primary transition-all cursor-pointer group/add">
                                                <x-icon name="plus" style="solid" class="w-6 h-6 text-gray-400 group-hover/add:text-primary transition-colors" />
                                            </label>
                                        @endif
                                    </div>
                                    <p id="upload-status" class="mt-4 text-[9px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500 text-center">
                                        @if($user->photos->count() < 3)
                                            Adicione até {{ 3 - $user->photos->count() }} foto(s) para alternar seu visual • JPG, PNG ou GIF • Máx. 2MB
                                        @else
                                            Limite de fotos atingido
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </section>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Floating Action Bar --}}
    @if(!session()->has('impersonate_inspection_id'))
        <div class="fixed bottom-8 left-0 right-0 z-50 px-4 pointer-events-none">
            <div class="max-w-7xl mx-auto flex justify-end pointer-events-auto">
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl p-2 rounded-2xl border border-white dark:border-slate-700 shadow-2xl flex items-center gap-4">
                    <button type="button" onclick="window.location='{{ route('user.profile.show') }}'"
                        class="px-6 py-2.5 text-sm font-black text-gray-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white uppercase tracking-widest transition-colors">
                        Descartar
                    </button>
                    <button type="button" onclick="document.getElementById('profile-form').submit()"
                        class="px-8 py-2.5 bg-primary text-white rounded-xl font-black text-sm uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary/90 transition-all active:scale-95">
                        Salvar Mudanças
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="fixed bottom-8 left-0 right-0 z-50 px-4 pointer-events-none">
            <div class="max-w-7xl mx-auto flex justify-end pointer-events-auto">
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl p-2 rounded-2xl border border-white dark:border-slate-700 shadow-2xl flex items-center gap-4">
                    <span class="text-xs font-bold text-amber-500 uppercase tracking-widest italic px-2">Modo Somente Leitura</span>
                    <button type="button" disabled class="px-8 py-2.5 bg-slate-300 dark:bg-slate-600 text-slate-500 rounded-xl font-black text-sm uppercase cursor-not-allowed">
                        Ação Bloqueada
                    </button>
                </div>
            </div>
        </div>
    @endif

    <style>
        .modern-input:focus {
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            transform: translateY(-2px);
        }
    </style>

    @push('scripts')
    <script>
        const tips = {
            'personal': 'Seus dados básicos são usados para identificação na plataforma. Mantenha nome e sobrenome sempre atualizados.',
            'contact': 'Garantir que seu telefone esteja correto é importante para comunicações. E-mail e CPF são protegidos e só podem ser alterados pelo suporte.',
            'photos': 'Use fotos nítidas. Você pode ter até 3 fotos e alternar qual será exibida como principal. Clique em uma miniatura para definir como ativa.'
        };

        function scrollToSection(id) {
            const el = document.getElementById(id);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                updateNavUI(id);
            }
        }

        function updateNavUI(id) {
            document.querySelectorAll('.nav-item').forEach(btn => {
                const navId = btn.getAttribute('data-nav');
                const icon = btn.querySelector('svg');

                if (navId === id) {
                    btn.classList.add('bg-primary/10', 'dark:bg-primary/20', 'text-primary', 'border-primary');
                    btn.classList.remove('border-transparent', 'text-gray-500', 'dark:text-slate-400', 'hover:bg-gray-50', 'dark:hover:bg-slate-800');
                    if (icon) {
                        icon.classList.remove('text-gray-400');
                        icon.classList.add('text-primary');
                    }
                } else {
                    btn.classList.remove('bg-primary/10', 'dark:bg-primary/20', 'text-primary', 'border-primary');
                    btn.classList.add('border-transparent', 'text-gray-500', 'dark:text-slate-400', 'hover:bg-gray-50', 'dark:hover:bg-slate-800');
                    if (icon) {
                        icon.classList.add('text-gray-400');
                        icon.classList.remove('text-primary');
                    }
                }
            });

            const tipBox = document.getElementById('contextual-tip');
            const tipText = document.getElementById('tip-text');

            if (tipBox && tipText) {
                tipBox.classList.remove('opacity-100', 'translate-y-0');
                tipBox.classList.add('opacity-0', 'translate-y-2');

                setTimeout(() => {
                    tipText.innerText = tips[id] || tips['personal'];
                    tipBox.classList.remove('opacity-0', 'translate-y-2', 'hidden');
                    tipBox.classList.add('opacity-100', 'translate-y-0');
                }, 150);
            }
        }

        let lastActiveSection = 'personal';
        window.addEventListener('scroll', () => {
            const sections = ['personal', 'contact', 'photos'];
            let current = lastActiveSection;
            const threshold = 150;

            for (const s of sections) {
                const el = document.getElementById(s);
                if (el) {
                    const rect = el.getBoundingClientRect();
                    if (rect.top <= threshold && rect.bottom >= threshold) {
                        current = s;
                    }
                }
            }

            if (current !== lastActiveSection) {
                lastActiveSection = current;
                updateNavUI(current);
            }
        });

        document.addEventListener('DOMContentLoaded', () => updateNavUI('personal'));
    </script>
    @endpush

    {{-- Hidden forms for photo actions --}}
    <form id="photo-upload-form" action="{{ route('user.profile.photo.upload') }}" method="POST" enctype="multipart/form-data" class="hidden">
        @csrf
        <input type="file" name="photos[]" id="photo-upload" accept="image/*" multiple onchange="if(this.files.length > 0) this.form.submit()">
    </form>

    @foreach($user->photos as $photo)
        <form id="activate-photo-{{ $photo->id }}" action="{{ route('user.profile.photo.active', $photo->id) }}" method="POST" class="hidden">
            @csrf @method('PATCH')
        </form>
        <form id="delete-photo-{{ $photo->id }}" action="{{ route('user.profile.photo.delete', $photo->id) }}" method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
    @endforeach

    {{-- Link Segurança --}}
    <div class="max-w-7xl mx-auto px-6 pb-8 mt-8">
        <a href="{{ route('user.security.index') }}" class="flex items-center gap-4 p-5 rounded-2xl bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 hover:border-primary/30 dark:hover:border-primary/30 transition-colors group shadow-sm">
            <div class="p-3 bg-slate-100 dark:bg-slate-800 rounded-xl group-hover:bg-primary/10 transition-colors">
                <x-icon name="shield-halved" style="solid" class="w-6 h-6 text-slate-500 dark:text-slate-400 group-hover:text-primary transition-colors" />
            </div>
            <div class="flex-1">
                <h4 class="text-base font-bold text-gray-900 dark:text-white">Segurança e Atividade</h4>
                <p class="text-sm text-slate-500 dark:text-slate-400">Alterar senha, autorizar acesso do suporte (incl. alteração de e-mail/CPF) e histórico de acessos.</p>
            </div>
            <x-icon name="chevron-right" style="solid" class="w-6 h-6 text-slate-400" />
        </a>
    </div>
</x-paneluser::layouts.master>
