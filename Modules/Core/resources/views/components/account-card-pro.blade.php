@props([
    'account',
    'gradient' => 'from-emerald-600 to-teal-700',
    'showActions' => true,
])

@php
    $cardIcon = match($account->type) {
        'cash' => 'money-bill-wave',
        'savings' => 'piggy-bank',
        default => 'credit-card'
    };
    $typeLabel = $account->type === 'checking' ? 'Corrente' : ($account->type === 'savings' ? 'Poupança' : 'Dinheiro');
    $displayNumber = '•••• •••• •••• ' . str_pad((string) ($account->id % 10000), 4, '0', STR_PAD_LEFT);
@endphp

<div class="account-card-pro group relative w-full max-w-[340px] h-[220px] mx-auto" x-data="{ flipped: false }" @mouseenter="if ($refs.card) flipped = true" @mouseleave="flipped = false">
    <div class="relative w-full h-full cursor-pointer" style="perspective: 2000px;">
        {{-- Front --}}
        <div class="absolute inset-0 rounded-2xl overflow-hidden bg-gradient-to-br {{ $gradient }} shadow-xl transition-all duration-700 ease-[cubic-bezier(0.71,0.03,0.56,0.85)]"
             style="transform-style: preserve-3d; backface-visibility: hidden;"
             :style="flipped ? 'transform: rotateY(-180deg);' : 'transform: rotateY(0deg);'"
             x-ref="card">
            <div class="absolute inset-0 bg-[rgba(6,2,29,0.35)]"></div>
            <div class="relative z-10 h-full flex flex-col justify-between p-5 text-white">
                <div class="flex items-start justify-between">
                    {{-- Chip --}}
                    <div class="w-12 h-10 rounded-md bg-gradient-to-br from-amber-200 via-amber-100 to-amber-300 shadow-inner flex items-center justify-center border border-amber-400/40">
                        <div class="grid grid-cols-2 gap-0.5 w-8 h-6">
                            @for ($i = 0; $i < 8; $i++)
                                <div class="bg-amber-600/50 rounded-sm"></div>
                            @endfor
                        </div>
                    </div>
                    <x-icon name="cc-visa" style="brands" class="w-10 h-10 opacity-95" />
                </div>
                <div>
                    <p class="sensitive-value text-lg font-mono font-semibold tracking-[0.2em] tabular-nums mb-4" style="text-shadow: 0 2px 8px rgba(0,0,0,0.3);">{{ $displayNumber }}</p>
                    <div class="flex items-end justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-[10px] text-white/70 uppercase tracking-widest mb-0.5">Nome no cartão</p>
                            <p class="font-semibold text-sm uppercase truncate tracking-wide" style="text-shadow: 0 2px 6px rgba(0,0,0,0.4);">{{ Str::upper($account->name) }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-[9px] text-white/70 uppercase tracking-wider">Saldo</p>
                            <p class="sensitive-value text-base font-mono font-black tabular-nums leading-tight" style="text-shadow: 0 2px 6px rgba(0,0,0,0.4);">R$ {{ number_format($account->balance, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Back --}}
        <div class="absolute inset-0 rounded-2xl overflow-hidden bg-gradient-to-br {{ $gradient }} shadow-xl transition-all duration-700 ease-[cubic-bezier(0.71,0.03,0.56,0.85)]"
             style="transform-style: preserve-3d; backface-visibility: hidden; transform: rotateY(180deg);"
             :style="flipped ? 'transform: rotateY(0deg);' : 'transform: rotateY(180deg);'">
            <div class="absolute inset-0 bg-[rgba(6,2,29,0.35)]"></div>
            <div class="relative z-10 h-full flex flex-col">
                <div class="w-full h-12 mt-6 bg-black/70 shrink-0"></div>
                <div class="flex-1 px-4 py-3 flex flex-col justify-end items-end">
                    <div class="w-full max-w-[90%] h-10 bg-white/95 rounded flex items-center justify-end px-3 text-slate-800 font-mono text-sm font-semibold">
                        <span class="text-amber-600 dark:text-amber-600 font-bold">Vertex Pro</span>
                    </div>
                    <div class="mt-3 flex justify-end">
                        <x-icon name="cc-visa" style="brands" class="w-9 h-9 text-white/90" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($showActions)
        <div class="absolute inset-0 rounded-2xl bg-black/50 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 z-20 pointer-events-none">
            <span class="pointer-events-auto">
                <a href="{{ route('core.accounts.show', $account) }}" class="p-3 bg-white/25 hover:bg-white/35 rounded-xl text-white transition-colors inline-flex" title="Ver">
                    <x-icon name="eye" style="solid" class="w-5 h-5" />
                </a>
            </span>
            <span class="pointer-events-auto">
                <a href="{{ route('core.accounts.edit', $account) }}" class="p-3 bg-white/25 hover:bg-white/35 rounded-xl text-white transition-colors inline-flex" title="Editar">
                    <x-icon name="pencil" style="solid" class="w-5 h-5" />
                </a>
            </span>
            <span class="pointer-events-auto">
                <form action="{{ route('core.accounts.destroy', $account) }}" method="POST" class="inline" onsubmit="return confirm('Excluir esta conta? Ela não pode ter transações.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-3 bg-red-500/40 hover:bg-red-500/60 rounded-xl text-white transition-colors inline-flex" title="Excluir">
                        <x-icon name="trash-can" style="solid" class="w-5 h-5" />
                    </button>
                </form>
            </span>
        </div>
    @endif
</div>
