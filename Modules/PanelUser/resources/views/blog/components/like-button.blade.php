@props(['post'])

@auth
<div x-data="{
    liked: {{ $post->isLikedBy(auth()->user()) ? 'true' : 'false' }},
    count: {{ $post->likes()->count() }},
    loading: false,
    async toggle() {
        if (this.loading) return;
        this.loading = true;
        try {
            const r = await fetch('{{ route('paneluser.blog.like.toggle', $post) }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await r.json();
            if (data.success) { this.liked = data.liked; this.count = data.count; }
        } finally { this.loading = false; }
    }
}" class="flex items-center gap-1.5">
    <button type="button" @click="toggle()" :disabled="loading"
        class="flex items-center gap-1.5 text-slate-500 hover:text-red-500 transition-colors disabled:opacity-50"
        :class="{ 'text-red-500': liked }">
        <x-icon name="heart" style="solid" class="w-5 h-5 transition-transform" x-bind:class="liked ? 'fill-current' : ''" />
        <span x-text="count"></span>
    </button>
</div>
@endauth
