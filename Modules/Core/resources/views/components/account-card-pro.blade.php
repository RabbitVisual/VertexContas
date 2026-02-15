@props([
    'account',
    'gradient' => 'from-emerald-600 to-teal-700',
    'showActions' => true,
])

@php
    $shouldHide = \Modules\Core\Services\InspectionGuard::shouldHideFinancialData();
    $displayNumber = $shouldHide ? '•••• •••• •••• ••••' : '•••• •••• •••• ' . str_pad((string) ($account->id % 10000), 4, '0', STR_PAD_LEFT);
    $user = auth()->user();
    $profileName = $user && trim($user->full_name) !== '' ? $user->full_name : '';
@endphp

{{-- Apenas frente do cartão — padrão Brasil Code, sem flip; logo Vertex em destaque --}}
<div class="account-card-pro group relative w-full max-w-[352px] h-[222px] mx-auto">
    <div class="relative w-full h-full rounded-[1rem] overflow-hidden bg-gradient-to-br {{ $gradient }} shadow-xl">
        {{-- Overlay escuro --}}
        <div class="absolute inset-0 bg-[rgba(6,2,29,0.35)]"></div>

        {{-- Logo Vertex em marca d'água (fundo, canto direito) --}}
        <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-6 w-28 h-28 opacity-20 pointer-events-none" aria-hidden="true">
            <img src="{{ asset('storage/logos/icon.svg') }}" alt="" class="w-full h-full object-contain" width="112" height="112" />
        </div>

        <div class="relative z-10 h-full flex flex-col justify-between p-5 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.4);">
            {{-- Topo: chip, logo Vertex, Visa --}}
            <div class="flex items-start justify-between gap-2">
                <div class="flex items-center gap-2.5 shrink-0">
                    <div class="w-11 h-9 rounded-md bg-gradient-to-br from-amber-200 via-amber-100 to-amber-300 shadow-inner flex items-center justify-center border border-amber-400/40">
                        <div class="grid grid-cols-2 gap-0.5 w-7 h-5">
                            @for ($i = 0; $i < 8; $i++)
                                <div class="bg-amber-600/50 rounded-sm"></div>
                            @endfor
                        </div>
                    </div>
                    <img src="{{ asset('storage/logos/icon.svg') }}" alt="Vertex Contas" class="w-7 h-7 shrink-0 drop-shadow-md" width="28" height="28" />
                </div>
                <x-icon name="cc-visa" style="brands" class="w-9 h-9 opacity-95 shrink-0" />
            </div>

            {{-- Número + titular + saldo --}}
            <div>
                <p class="sensitive-value text-base font-mono font-semibold tracking-[0.22em] tabular-nums mb-4 {{ \Modules\Core\Services\InspectionGuard::maskClasses() }}">{{ $displayNumber }}</p>
                <div class="flex items-end justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-sm uppercase truncate tracking-wide">{{ Str::upper($account->name) }}</p>
                        @if($profileName !== '')
                            <p class="text-[10px] text-white/80 truncate mt-0.5">{{ $profileName }}</p>
                        @endif
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-[9px] text-white/80 uppercase tracking-wider">Saldo</p>
                        <p class="sensitive-value text-sm font-mono font-black tabular-nums leading-tight"><x-core::financial-value :value="$account->balance" /></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($showActions)
        <div class="absolute inset-0 rounded-[1rem] bg-black/50 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center gap-3 z-20 pointer-events-none">
            <a href="{{ route('core.accounts.show', $account) }}" class="pointer-events-auto p-3 bg-white/25 hover:bg-white/35 rounded-xl text-white transition-colors inline-flex" title="Ver">
                <x-icon name="eye" style="solid" class="w-5 h-5" />
            </a>
            @if(!($inspectionReadOnly ?? false))
                <a href="{{ route('core.accounts.edit', $account) }}" class="pointer-events-auto p-3 bg-white/25 hover:bg-white/35 rounded-xl text-white transition-colors inline-flex" title="Editar">
                    <x-icon name="pencil" style="solid" class="w-5 h-5" />
                </a>
                <form action="{{ route('core.accounts.destroy', $account) }}" method="POST" class="pointer-events-auto inline" onsubmit="return confirm('Excluir esta conta? Ela não pode ter transações.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-3 bg-red-500/40 hover:bg-red-500/60 rounded-xl text-white transition-colors inline-flex" title="Excluir">
                        <x-icon name="trash-can" style="solid" class="w-5 h-5" />
                    </button>
                </form>
            @endif
        </div>
    @endif
</div>
