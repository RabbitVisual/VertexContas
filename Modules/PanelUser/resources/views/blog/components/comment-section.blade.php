@props(['post'])

<div class="mt-8 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white/80 dark:bg-slate-900/80 backdrop-blur p-6"
    x-data="{
        comments: @js($post->approvedComments->map(fn($c) => ['id' => $c->id, 'user' => ['name' => $c->user->name], 'content' => $c->content, 'created_at' => $c->created_at->diffForHumans()])->values()->all()),
        content: '',
        loading: false,
        async submit() {
            if (!this.content.trim() || this.loading) return;
            this.loading = true;
            try {
                const r = await fetch('{{ route('paneluser.blog.comment.store', $post) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: JSON.stringify({ content: this.content })
                });
                const data = await r.json();
                if (data.success && data.comment) {
                    this.comments.unshift({ id: data.comment.id, user: { name: data.comment.user.name }, content: data.comment.content, created_at: 'Agora' });
                    this.content = '';
                } else if (data.success) {
                    alert(data.message);
                }
            } finally { this.loading = false; }
        }
    }">
    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
        <x-icon name="comments" style="duotone" class="w-6 h-6 text-amber-500" />
        Comentários
    </h3>

    <form @submit.prevent="submit()" class="mb-8">
        <textarea x-model="content" rows="3" placeholder="Deixe seu comentário..."
            class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 px-4 py-3"></textarea>
        <div class="flex justify-end mt-2">
            <button type="submit" :disabled="loading || !content.trim()"
                class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold disabled:opacity-50 transition-colors">
                Publicar
            </button>
        </div>
    </form>

    <div class="space-y-6" id="comments-list">
        <template x-for="comment in comments" :key="comment.id">
            <div class="flex gap-4">
                <div class="shrink-0 w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 font-bold text-sm"
                    x-text="(comment.user?.name || '').charAt(0)"></div>
                <div class="flex-1">
                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-800/50 px-4 py-3">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-bold text-slate-900 dark:text-white" x-text="comment.user?.name"></span>
                            <span class="text-xs text-slate-500" x-text="comment.created_at"></span>
                        </div>
                        <p class="text-sm text-slate-700 dark:text-slate-300" x-text="comment.content"></p>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
