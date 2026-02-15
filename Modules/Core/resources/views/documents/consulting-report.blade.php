@extends('core::documents._layout')

@section('documentTitle', 'Consultoria Financeira')
@section('documentRightLabel', 'Período')
@section('documentRightValue', $consultingData['period_label'] ?? now()->locale('pt_BR')->translatedFormat('F Y'))
@section('clientName', auth()->user()->name ?? '—')
@section('clientEmail', lgpd_mask_email(auth()->user()->email ?? null))

@section('content')
@php
    $budget = $consultingData['budget_analysis'] ?? [];
    $score = $consultingData['financial_score'] ?? 0;
    $recommendations = $consultingData['recommendations'] ?? [];
    $medals = $consultingData['medals'] ?? collect();
    $pillars = $budget['pillars'] ?? [];
@endphp

{{-- Financial Score Gauge --}}
<div style="page-break-inside: avoid; margin-bottom: 28px;">
    <h3 class="section-title">Score Financeiro</h3>
    <div style="display: flex; align-items: center; gap: 24px; flex-wrap: wrap;">
        <div style="position: relative; width: 140px; height: 80px;">
            <svg viewBox="0 0 200 100" style="width: 100%; height: 100%;">
                <defs>
                    <linearGradient id="gaugeBg" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" style="stop-color:#e5e7eb" />
                        <stop offset="100%" style="stop-color:#d1d5db" />
                    </linearGradient>
                    <linearGradient id="gaugeFillRed" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" style="stop-color:#ef4444" />
                        <stop offset="100%" style="stop-color:#dc2626" />
                    </linearGradient>
                    <linearGradient id="gaugeFillAmber" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" style="stop-color:#f59e0b" />
                        <stop offset="100%" style="stop-color:#d97706" />
                    </linearGradient>
                    <linearGradient id="gaugeFillGreen" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" style="stop-color:#22c55e" />
                        <stop offset="100%" style="stop-color:#16a34a" />
                    </linearGradient>
                </defs>
                <path d="M 20 90 A 80 80 0 0 1 180 90" fill="none" stroke="url(#gaugeBg)" stroke-width="12" stroke-linecap="round" />
                @php
                    $pct = min(100, max(0, $score)) / 100;
                    $angle = 180 * (1 - $pct);
                    $rad = deg2rad($angle);
                    $cx = 100; $cy = 90; $r = 80;
                    $x2 = $cx + $r * cos($rad);
                    $y2 = $cy + $r * sin($rad);
                    $fillColor = $score <= 40 ? 'url(#gaugeFillRed)' : ($score <= 70 ? 'url(#gaugeFillAmber)' : 'url(#gaugeFillGreen)');
                @endphp
                <path d="M 20 90 A 80 80 0 0 1 {{ $x2 }} {{ $y2 }}" fill="none" stroke="{{ $fillColor }}" stroke-width="12" stroke-linecap="round" />
            </svg>
            <div style="position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); font-size: 24px; font-weight: 800; color: #1e293b;">{{ $score }}</div>
        </div>
        <div style="flex: 1; min-width: 160px;">
            <p style="margin: 0; font-size: 11px; color: #64748b; line-height: 1.5;">Seu score de 0 a 100 reflete aderência a orçamentos, taxa de poupança e reserva de emergência.</p>
            <div style="margin-top: 8px; font-size: 10px; color: #94a3b8;">Renda base: {{ format_currency($budget['baseline_income'] ?? 0) }} · Despesas: {{ format_currency($budget['total_expenses'] ?? 0) }}</div>
        </div>
    </div>
</div>

{{-- 50/30/20 Section --}}
<h3 class="section-title">Análise 50/30/20 — Meta vs Real</h3>
<table>
    <thead>
        <tr class="heading-row">
            <th>Pilar</th>
            <th class="text-right">Meta</th>
            <th class="text-right">Real</th>
            <th class="text-right">Desvio</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach(['essential' => 'Essencial', 'lifestyle' => 'Estilo de Vida', 'financial' => 'Financeiro'] as $key => $label)
        @php $p = $pillars[$key] ?? []; @endphp
        <tr class="item-row">
            <td>{{ $label }}</td>
            <td class="text-right">{{ format_percent($p['target_pct'] ?? 0, 1) }}</td>
            <td class="text-right">{{ format_percent($p['actual_pct'] ?? 0, 1) }}</td>
            <td class="text-right">{{ ($p['deviation'] ?? 0) >= 0 ? '+' : '' }}{{ format_percent($p['deviation'] ?? 0, 1) }}</td>
            <td>
                @php $status = $p['status'] ?? 'ok'; @endphp
                @if($status === 'over')
                    <span style="color: #dc2626; font-weight: 600;">Acima</span>
                @elseif($status === 'under')
                    <span style="color: #d97706; font-weight: 600;">Abaixo</span>
                @else
                    <span style="color: #16a34a; font-weight: 600;">Ok</span>
                @endif
            </td>
        </tr>
        @endforeach
        <tr class="item-row">
            <td>Poupança (sobra)</td>
            <td class="text-right">—</td>
            <td class="text-right" style="color: {{ ($budget['savings_pct'] ?? 0) >= 20 ? '#16a34a' : '#64748b' }};">{{ format_percent($budget['savings_pct'] ?? 0, 1) }}</td>
            <td class="text-right">—</td>
            <td>
                @if(($budget['savings_pct'] ?? 0) >= 20)
                    <span style="color: #16a34a; font-weight: 600;">Alvo atingido</span>
                @else
                    <span style="color: #64748b;">Meta 20%</span>
                @endif
            </td>
        </tr>
    </tbody>
</table>

{{-- Recommendations --}}
@if($recommendations)
<h3 class="section-title">Recomendações</h3>
<ol style="margin: 0; padding-left: 20px; color: #334155; font-size: 11px; line-height: 1.6;">
    @foreach($recommendations as $rec)
    <li style="margin-bottom: 8px;">{{ $rec }}</li>
    @endforeach
</ol>
@endif

{{-- Achievements / Medals --}}
@if($medals->isNotEmpty())
<h3 class="section-title">Conquistas do Período</h3>
<div style="display: flex; flex-direction: column; gap: 12px;">
    @foreach($medals as $medal)
    <div style="display: flex; align-items: flex-start; gap: 12px; padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; page-break-inside: avoid;">
        <div style="width: 32px; height: 32px; border-radius: 8px; background: {{ $medal['color'] ?? '#64748b' }}; flex-shrink: 0;" title="{{ $medal['icon'] ?? 'medalha' }}"></div>
        <div style="flex: 1;">
            <div style="font-weight: 700; color: #1e293b; margin-bottom: 2px;">{{ $medal['title'] ?? '—' }}</div>
            @if(!empty($medal['description']))
            <div style="font-size: 10px; color: #64748b;">{{ $medal['description'] }}</div>
            @endif
            <div style="font-size: 9px; color: #94a3b8; margin-top: 4px;">Conquistado em {{ ($medal['unlocked_at'] ?? null)?->format('d/m/Y') ?? '—' }}</div>
        </div>
    </div>
    @endforeach
</div>
@else
<h3 class="section-title">Conquistas do Período</h3>
<p style="margin: 0; color: #64748b; font-size: 11px;">Nenhuma medalha conquistada neste mês. Continue seguindo as recomendações para desbloquear conquistas.</p>
@endif
@endsection
