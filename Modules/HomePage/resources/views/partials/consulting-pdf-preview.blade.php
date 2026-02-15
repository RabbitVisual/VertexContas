{{-- Static mockup of PDF Consultoria for landing page --}}
@php
    $previewScore = 72;
    $previewPillars = [
        'essential' => ['target_pct' => 50, 'actual_pct' => 48, 'status' => 'ok'],
        'lifestyle' => ['target_pct' => 30, 'actual_pct' => 32, 'status' => 'over'],
        'financial' => ['target_pct' => 20, 'actual_pct' => 20, 'status' => 'ok'],
    ];
@endphp
<div class="relative rounded-xl border border-slate-600/50 bg-slate-900/80 p-4 overflow-hidden" style="min-height: 140px;">
    <div class="absolute top-1 right-2 text-[10px] text-slate-500 font-bold">CONSULTORIA FINANCEIRA</div>
    <div class="flex items-center gap-3 mb-3">
        <div class="relative w-16 h-10 shrink-0">
            <svg viewBox="0 0 200 100" class="w-full h-full -scale-y-100">
                <path d="M 20 90 A 80 80 0 0 1 180 90" fill="none" stroke="#334155" stroke-width="6" stroke-linecap="round" />
                @php
                    $pct = $previewScore / 100;
                    $angle = 180 * (1 - $pct);
                    $rad = deg2rad($angle);
                    $cx = 100; $cy = 90; $r = 80;
                    $x2 = $cx + $r * cos($rad);
                    $y2 = $cy + $r * sin($rad);
                @endphp
                <path d="M 20 90 A 80 80 0 0 1 {{ $x2 }} {{ $y2 }}" fill="none" stroke="#22c55e" stroke-width="6" stroke-linecap="round" />
            </svg>
            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 text-xs font-black text-white">{{ $previewScore }}</span>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-[10px] text-slate-400 leading-tight">Score 0-100 · Análise 50/30/20</p>
        </div>
    </div>
    <table class="w-full text-[9px]">
        <thead>
            <tr class="text-slate-500 border-b border-slate-600">
                <th class="text-left py-1">Pilar</th>
                <th class="text-right">Meta</th>
                <th class="text-right">Real</th>
            </tr>
        </thead>
        <tbody class="text-slate-300">
            @foreach($previewPillars as $label => $p)
            <tr class="border-b border-slate-700/50">
                <td class="py-0.5">{{ $label === 'essential' ? 'Essencial' : ($label === 'lifestyle' ? 'Estilo de Vida' : 'Financeiro') }}</td>
                <td class="text-right">{{ $p['target_pct'] }}%</td>
                <td class="text-right">{{ $p['actual_pct'] }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
