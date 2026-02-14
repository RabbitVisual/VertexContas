@props([
    'account',
    'gradient' => 'from-emerald-600 to-teal-700',
    'showActions' => true,
])

@php
    $displayNumber = '•••• •••• •••• ' . str_pad((string) ($account->id % 10000), 4, '0', STR_PAD_LEFT);
@endphp

{{-- Proporção ~1.586 (cartão físico). Animação: 0.6s, curva suave sem overshoot --}}
<div class="account-card-pro group relative w-full max-w-[352px] h-[222px] mx-auto" x-data="{ flipped: false }">
    <div class="relative w-full h-full cursor-pointer select-none" style="perspective: 2200px;" @click="flipped = !flipped" title="Clique para virar o cartão">
        {{-- Frente --}}
        <div class="absolute inset-0 rounded-[1rem] overflow-hidden bg-gradient-to-br {{ $gradient }} shadow-xl"
             style="transform-style: preserve-3d; backface-visibility: hidden; transition: transform 0.55s cubic-bezier(0.45, 0, 0.25, 1);"
             :style="flipped ? 'transform: rotateY(-180deg);' : 'transform: rotateY(0deg);'"
             x-ref="card">
            <div class="absolute inset-0 bg-[rgba(6,2,29,0.32)]"></div>
            <div class="relative z-10 h-full flex flex-col justify-between p-5 text-white">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex items-center gap-2 shrink-0">
                        <img src="{{ asset('storage/logos/icon.svg') }}" alt="" class="w-6 h-6 shrink-0 drop-shadow-md" width="24" height="24" />
                        <div class="w-11 h-9 rounded-md bg-gradient-to-br from-amber-200 via-amber-100 to-amber-300 shadow-inner flex items-center justify-center border border-amber-400/40">
                            <div class="grid grid-cols-2 gap-0.5 w-7 h-5">
                                @for ($i = 0; $i < 8; $i++)
                                    <div class="bg-amber-600/50 rounded-sm"></div>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <x-icon name="cc-visa" style="brands" class="w-9 h-9 opacity-95 shrink-0" />
                </div>
                <div>
                    <p class="sensitive-value text-base font-mono font-semibold tracking-[0.22em] tabular-nums mb-3" style="text-shadow: 0 2px 8px rgba(0,0,0,0.3);">{{ $displayNumber }}</p>
                    <div class="flex items-end justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] text-white/75 uppercase tracking-widest mb-0.5">Nome no cartão</p>
                            <p class="font-semibold text-sm uppercase truncate tracking-wide" style="text-shadow: 0 2px 6px rgba(0,0,0,0.4);">{{ Str::upper($account->name) }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-[9px] text-white/75 uppercase tracking-wider">Saldo</p>
                            <p class="sensitive-value text-sm font-mono font-black tabular-nums leading-tight" style="text-shadow: 0 2px 6px rgba(0,0,0,0.4);">R$ {{ number_format($account->balance, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Verso --}}
        <div class="absolute inset-0 rounded-[1rem] overflow-hidden bg-gradient-to-br {{ $gradient }} shadow-xl"
             style="transform-style: preserve-3d; backface-visibility: hidden; transform: rotateY(180deg); transition: transform 0.55s cubic-bezier(0.45, 0, 0.25, 1);"
             :style="flipped ? 'transform: rotateY(0deg);' : 'transform: rotateY(180deg);'">
            <div class="absolute inset-0 bg-[rgba(6,2,29,0.32)]"></div>
            <div class="relative z-10 h-full flex flex-col">
                <div class="w-full h-11 mt-5 bg-black/75 shrink-0" aria-hidden="true"></div>
                <div class="flex-1 px-4 py-3 flex flex-col justify-end items-end gap-2">
                    <div class="w-full max-w-[92%] h-9 bg-white/95 rounded-md flex items-center justify-end gap-2 px-3 text-slate-800">
                        <img src="{{ asset('storage/logos/icon.svg') }}" alt="" class="w-5 h-5 shrink-0" width="20" height="20" />
                        <span class="text-xs font-bold text-emerald-700">Vertex Contas</span>
                        <span class="text-slate-400">•</span>
                        <span class="text-xs font-bold text-amber-600">Pro</span>
                    </div>
                    <p class="text-[10px] text-white/60 uppercase tracking-wider">Assinante</p>
                    <div class="flex justify-end">
                        <x-icon name="cc-visa" style="brands" class="w-8 h-8 text-white/90" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($showActions)
        <div class="absolute inset-0 rounded-[1rem] bg-black/50 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center gap-3 z-20 pointer-events-none group-hover:pointer-events-auto">
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
