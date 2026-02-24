<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Editar Post</x-slot>

    <x-paneladmin::page title="Editar Post" :subtitle="Str::limit($post->title, 50)">
        @if(session('error'))
            <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-center gap-4 text-amber-600 dark:text-amber-400 mb-6" role="alert">
                <x-icon name="triangle-exclamation" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <x-slot name="header">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.blog.show', $post) }}" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors flex items-center gap-2">
                    <x-icon name="eye" style="duotone" class="w-4 h-4" /> Ver post
                </a>
                <a href="{{ route('admin.blog.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors flex items-center gap-2">
                    <x-icon name="arrow-left" style="duotone" class="w-4 h-4" /> Voltar
                </a>
            </div>
        </x-slot>

        <form action="{{ route('admin.blog.update', $post) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            @method('PUT')

            <div class="lg:col-span-2 space-y-6">
                <x-paneladmin::card title="Conteúdo" subtitle="Título e corpo do artigo.">
                    <div class="p-6 space-y-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Título</label>
                            <input type="text" name="title" required value="{{ old('title', $post->title) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Conteúdo</label>
                            <textarea name="content" id="editor" rows="18" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium text-sm resize-none focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">{{ old('content', $post->content) }}</textarea>
                        </div>
                    </div>
                </x-paneladmin::card>

                <x-paneladmin::card title="SEO" subtitle="Meta descrição e imagem para redes sociais.">
                    <div class="p-6 space-y-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Meta Descrição</label>
                            <textarea name="meta_description" rows="2" maxlength="160" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium text-sm resize-none focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">{{ old('meta_description', $post->meta_description) }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Imagem OG</label>
                            @if($post->og_image)
                                <div class="mb-2 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 w-32">
                                    <img src="{{ asset($post->og_image) }}" alt="OG" class="w-full h-20 object-cover">
                                </div>
                            @endif
                            <input type="file" name="og_image" accept="image/*" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-bold file:bg-[#11C76F]/10 file:text-[#11C76F]">
                        </div>
                    </div>
                </x-paneladmin::card>
            </div>

            <div class="space-y-6">
                <x-paneladmin::card title="Publicação" subtitle="Categoria, status e opções.">
                    <div class="p-6 space-y-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Categoria</label>
                            <div class="relative">
                                <select name="category_id" required class="blog-select w-full pl-4 pr-10 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F] appearance-none [&::-ms-expand]:hidden">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <x-icon name="chevron-down" style="duotone" class="w-4 h-4 text-slate-400" />
                                </span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</label>
                            <div class="relative">
                                <select name="status" required class="blog-select w-full pl-4 pr-10 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F] appearance-none [&::-ms-expand]:hidden">
                                    <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Rascunho</option>
                                    <option value="pending_review" {{ old('status', $post->status) == 'pending_review' ? 'selected' : '' }}>Revisão Pendente</option>
                                    <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Publicado</option>
                                </select>
                                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <x-icon name="chevron-down" style="duotone" class="w-4 h-4 text-slate-400" />
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-700/30 border border-slate-100 dark:border-slate-700">
                            <div>
                                <span class="text-sm font-bold text-slate-900 dark:text-white block">Conteúdo Premium</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400">Apenas assinantes PRO</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_premium" value="1" class="sr-only peer" {{ old('is_premium', $post->is_premium) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 dark:bg-slate-600 rounded-full peer peer-checked:bg-[#11C76F] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                            </label>
                        </div>
                    </div>
                </x-paneladmin::card>

                <x-paneladmin::card title="Imagem de Destaque" subtitle="Recomendado 1200×630px.">
                    <div class="p-6">
                        <div class="w-full aspect-video rounded-xl border-2 border-dashed border-slate-200 dark:border-slate-600 flex flex-col items-center justify-center text-slate-400 hover:border-[#11C76F]/40 transition-colors cursor-pointer relative overflow-hidden group" onclick="document.getElementById('featured_image').click()">
                            @if($post->featured_image)
                                <img id="preview" src="{{ asset($post->featured_image) }}" class="absolute inset-0 w-full h-full object-cover" alt="">
                            @else
                                <x-icon name="cloud-arrow-up" style="duotone" class="w-10 h-10 mb-2 group-hover:text-[#11C76F] transition-colors" />
                                <span class="text-xs font-bold uppercase tracking-wider">Alterar imagem</span>
                                <img id="preview" class="hidden absolute inset-0 w-full h-full object-cover" alt="">
                            @endif
                            <input type="file" name="featured_image" id="featured_image" class="hidden" accept="image/*" onchange="previewImage(this)">
                        </div>
                    </div>
                </x-paneladmin::card>

                <button type="submit" class="w-full py-4 bg-[#11C76F] text-white font-black rounded-xl hover:bg-[#0EA85A] transition-colors flex items-center justify-center gap-2">
                    <x-icon name="floppy-disk" style="duotone" class="w-5 h-5" /> Salvar Alterações
                </button>
            </div>
        </form>
    </x-paneladmin::page>

    @push('styles')
    <style>
        .blog-select { appearance: none !important; -webkit-appearance: none !important; -moz-appearance: none !important; background-image: none !important; }
    </style>
    @endpush
    @push('scripts')
    <script>
        function previewImage(input) {
            var preview = document.getElementById('preview');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) { preview.src = e.target.result; preview.classList.remove('hidden'); };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @endpush
</x-paneladmin::layouts.master>
