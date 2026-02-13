<x-paneladmin::layouts.master>
    <div class="max-w-5xl mx-auto space-y-8 animate-in slide-in-from-bottom-4 duration-500">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.blog.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-900 border border-gray-100 dark:border-gray-800 flex items-center justify-center text-slate-400 hover:text-primary transition-all">
                <x-icon name="arrow-left" />
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">Editar Post</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1">Alterando: {{ $post->title }}</p>
            </div>
        </div>

        <form action="{{ route('admin.blog.update', $post) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            @method('PUT')

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white dark:bg-slate-900 rounded-[3rem] p-8 border border-gray-100 dark:border-gray-800 shadow-sm space-y-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Título do Artigo</label>
                        <input type="text" name="title" required value="{{ old('title', $post->title) }}" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold text-lg">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Conteúdo</label>
                        <textarea name="content" id="editor" rows="20" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-medium text-sm resize-none">{{ old('content', $post->content) }}</textarea>
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="bg-white dark:bg-slate-900 rounded-[3rem] p-8 border border-gray-100 dark:border-gray-800 shadow-sm space-y-6">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2">
                        <x-icon name="magnifying-glass" class="text-primary text-sm" /> Configurações de SEO
                    </h3>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Meta Descrição</label>
                        <textarea name="meta_description" rows="2" maxlength="160" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-medium text-sm resize-none">{{ old('meta_description', $post->meta_description) }}</textarea>
                    </div>

                     <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Imagem OG (Redes Sociais)</label>
                        @if($post->og_image)
                            <div class="mb-2 w-32 h-20 rounded-lg overflow-hidden border border-gray-100 dark:border-gray-800">
                                <img src="{{ asset($post->og_image) }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <input type="file" name="og_image" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] text-slate-500 font-bold text-sm">
                    </div>
                </div>
            </div>

            <!-- Sidebar Controls -->
            <div class="space-y-8">
                <!-- Status & Category -->
                <div class="bg-white dark:bg-slate-900 rounded-[3rem] p-8 border border-gray-100 dark:border-gray-800 shadow-sm space-y-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Categoria</label>
                        <select name="category_id" required class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold text-sm">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Status de Publicação</label>
                        <select name="status" required class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold text-sm">
                            <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Rascunho</option>
                            <option value="pending_review" {{ old('status', $post->status) == 'pending_review' ? 'selected' : '' }}>Revisão Pendente</option>
                            <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Publicado</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-800 rounded-2xl">
                        <div class="flex flex-col">
                            <span class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-tight">Conteúdo Premium</span>
                            <span class="text-[9px] text-slate-400 font-bold">Apenas assinantes Pro</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_premium" value="1" class="sr-only peer" {{ old('is_premium', $post->is_premium) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                        </label>
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="bg-white dark:bg-slate-900 rounded-[3rem] p-8 border border-gray-100 dark:border-gray-800 shadow-sm space-y-6">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Imagem de Destaque</label>
                    <div class="space-y-4">
                        <div class="w-full aspect-video bg-gray-50 dark:bg-slate-800 rounded-3xl border-2 border-dashed border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center text-slate-400 group hover:border-primary/50 transition-all cursor-pointer relative overflow-hidden" onclick="document.getElementById('featured_image').click()">
                            @if($post->featured_image)
                                <img id="preview" src="{{ asset($post->featured_image) }}" class="absolute inset-0 w-full h-full object-cover">
                            @else
                                <x-icon name="cloud-arrow-up" class="text-3xl mb-2 group-hover:scale-110 transition-transform" />
                                <span class="text-[10px] font-black uppercase tracking-widest">Alterar Imagem</span>
                                <img id="preview" class="hidden absolute inset-0 w-full h-full object-cover">
                            @endif
                            <input type="file" name="featured_image" id="featured_image" class="hidden" onchange="previewImage(this)">
                        </div>
                        <p class="text-[9px] text-slate-400 font-bold text-center italic">Recomendado: 1200x630px (Max 2MB)</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <button type="submit" class="w-full py-5 bg-primary text-white font-black rounded-3xl shadow-xl shadow-primary/20 hover:bg-primary-dark transition-all transform hover:-translate-y-1 active:scale-95">
                        Salvar Alterações
                    </button>

                    <button type="button" onclick="window.location.reload()" class="w-full py-4 border-2 border-gray-100 dark:border-gray-800 text-slate-400 font-black rounded-3xl text-sm hover:bg-gray-50 transition-all">
                        Descartar Mudanças
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @endpush
</x-paneladmin::layouts.master>
