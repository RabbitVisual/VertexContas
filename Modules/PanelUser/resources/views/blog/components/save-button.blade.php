@props(['post'])

@auth
<div x-data="{
    saved: {{ $post->isSavedBy(auth()->user()) ? 'true' : 'false' }},
    loading: false,
    async toggle() {
        if (this.loading) return;
        this.loading = true;
        try {
            const r = await fetch('{{ route('paneluser.blog.save.toggle', $post) }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await r.json();
            if (data.success) this.saved = data.saved;
        } finally { this.loading = false; }
    }
}">
    <button type="button" @click="toggle()" :disabled="loading"
        class="flex items-center gap-1.5 text-slate-500 hover:text-amber-500 transition-colors disabled:opacity-50"
        :class="saved ? 'text-amber-500' : ''">
        <x-icon name="bookmark" style="solid" class="w-5 h-5" />
        <span x-text="saved ? 'Salvo' : 'Salvar'"></span>
    </button>
</div>
@endauth
