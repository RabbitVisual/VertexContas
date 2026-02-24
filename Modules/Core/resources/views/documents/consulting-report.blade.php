@extends('core::documents._layout-business-statement')

@php
    $pillarsBrl = $consultingData['pillars_brl'] ?? [];
    $medals = $consultingData['medals'] ?? collect();
    $score = (int) ($consultingData['financial_score'] ?? 0);
    $aiConclusionText = is_array($recommendations) && count($recommendations) > 0 ? trim($recommendations[0]) : '';
    $aiProjection = $consultingData['ai_projection'] ?? null;
    $aiTips = $consultingData['ai_tips'] ?? [];
    $income = (float) ($metrics['income'] ?? 0);
    $expenses = (float) ($metrics['expense'] ?? 0);
    $flowFree = $income - $expenses;

    $pilarDescriptions = [
        'essential' => 'Aluguel, Luz, Internet',
        'lifestyle'  => 'Lazer, Streamings, Restaurantes',
        'financial' => 'Aportes, Reserva de Emergência',
    ];
    $pilarTargetPct = ['essential' => 50, 'lifestyle' => 30, 'financial' => 20];
@endphp

@section('documentTitle', 'Consultoria Financeira - Relatório PRO')
@section('documentRef', '#CONS-' . ($consultingData['period'] ?? now()->format('Y-m')))
@section('clientName', $user->name ?? '—')
@section('clientPlan', 'Plano Vertex PRO')
@section('emissionDate', now()->locale('pt_BR')->translatedFormat('d \d\e F, Y'))
@section('periodLabel', $consultingData['period_label'] ?? now()->locale('pt_BR')->translatedFormat('F Y'))

@section('docActions')
<a href="{{ route('core.reports.consultoria.pdf', request()->only(['period', 'nova'])) }}" class="btn-print" download>Baixar PDF</a>
@endsection

@section('content')
@if($medals->isNotEmpty())
{{-- Conquistas do mês (máx. 2, ícones reais SVG) --}}
<table class="bs-table" style="page-break-inside: avoid;">
    <thead>
        <tr>
            <th style="width: 48px;"></th>
            <th>Conquista</th>
            <th>Descrição</th>
            <th class="text-right">Desbloqueada em</th>
        </tr>
    </thead>
    <tbody>
        @foreach($medals->take(2) as $medal)
        @php $iconName = isset($medal['icon']) ? preg_replace('/^fa-/', '', $medal['icon']) : 'medal'; @endphp
        <tr class="row-zebra">
            <td style="vertical-align: middle;">
                @if($forPdf ?? false)
                    <span style="font-size: 12pt; color: {{ $medal['color'] ?? '#64748b' }};">*</span>
                @else
                    @include('core::components.medal-icon-svg', ['name' => $iconName, 'size' => 24, 'color' => $medal['color'] ?? '#64748b'])
                @endif
            </td>
            <td class="font-bold">{{ $medal['title'] ?? '—' }}</td>
            <td style="font-size: 10pt;">{{ $medal['description'] ?? '—' }}</td>
            <td class="text-right" style="font-variant-numeric: tabular-nums;">{{ isset($medal['unlocked_at']) ? \Carbon\Carbon::parse($medal['unlocked_at'])->format('d/m/Y') : '—' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- Tabela 50/30/20 — estilo planilha: categoria, %, valores, desvio, status --}}
<div class="pillar-table-wrap">
    <div class="pillar-table-title">Distribuição da Renda — Metodologia 50/30/20</div>
    @if(empty($forPdf))<div class="table-scroll-wrap">@endif
    <table class="bs-table bs-table-pillar">
        <colgroup>
            <col style="width: 22%;">
            <col style="width: 10%;">
            <col style="width: 14%;">
            <col style="width: 10%;">
            <col style="width: 14%;">
            <col style="width: 14%;">
            <col style="width: 16%;">
        </colgroup>
        <thead>
            <tr>
                <th>Categoria / Pilar</th>
                <th class="text-right">Meta %</th>
                <th class="text-right">Meta (R$)</th>
                <th class="text-right">Realizado %</th>
                <th class="text-right">Realizado (R$)</th>
                <th class="text-right">Desvio (R$)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalTarget = 0;
                $totalActual = 0;
            @endphp
            @foreach(['essential' => 'Essenciais', 'lifestyle' => 'Desejos Pessoais', 'financial' => 'Investimentos'] as $key => $label)
            @php
                $p = $pillarsBrl[$key] ?? ['label' => $label, 'target_brl' => 0, 'actual_brl' => 0, 'status' => 'ok'];
                $targetBrl = (float) ($p['target_brl'] ?? 0);
                $actualBrl = (float) ($p['actual_brl'] ?? 0);
                $totalTarget += $targetBrl;
                $totalActual += $actualBrl;
                $targetPct = $pilarTargetPct[$key] ?? 0;
                $actualPct = $income > 0 ? round($actualBrl / $income * 100, 1) : 0;
                $desvioBrl = $actualBrl - $targetBrl;
                $status = $p['status'] ?? 'ok';
                $badgeText = $status === 'ok' ? 'Dentro da Meta' : ($status === 'over' ? 'Excedido' : 'Atenção');
                $badgeStyle = $status === 'ok' ? 'color: #166534; background-color: #dcfce7;' : ($status === 'over' ? 'color: #991b1b; background-color: #fee2e2;' : 'color: #854d0e; background-color: #fef9c3;');
            @endphp
            <tr class="row-zebra">
                <td>
                    <div class="font-bold">{{ $p['label'] ?? $label }}</div>
                    <div style="font-size: 8pt; color: #64748b;">{{ $pilarDescriptions[$key] ?? '' }}</div>
                </td>
                <td class="text-right" style="font-variant-numeric: tabular-nums;">{{ $targetPct }}%</td>
                <td class="text-right" style="font-variant-numeric: tabular-nums;">{{ format_currency($targetBrl) }}</td>
                <td class="text-right" style="font-variant-numeric: tabular-nums;">{{ number_format($actualPct, 1, ',', '.') }}%</td>
                <td class="text-right font-bold" style="font-variant-numeric: tabular-nums;">{{ format_currency($actualBrl) }}</td>
                <td class="text-right pillar-desvio" style="font-variant-numeric: tabular-nums; {{ $desvioBrl > 0 ? 'color: #991b1b;' : ($desvioBrl < 0 ? 'color: #166534;' : 'color: #475569;') }}">{{ $desvioBrl >= 0 ? '+' : '' }}{{ format_currency($desvioBrl) }}</td>
                <td><span class="pilar-badge" style="{{ $badgeStyle }}">{{ $badgeText }}</span></td>
            </tr>
            @endforeach
            <tr class="pillar-total-row">
                <td class="font-bold">Total</td>
                <td class="text-right" style="font-variant-numeric: tabular-nums;">100%</td>
                <td class="text-right font-bold" style="font-variant-numeric: tabular-nums;">{{ format_currency($totalTarget) }}</td>
                <td class="text-right" style="font-variant-numeric: tabular-nums;">—</td>
                <td class="text-right font-bold" style="font-variant-numeric: tabular-nums;">{{ format_currency($totalActual) }}</td>
                <td class="text-right" style="font-variant-numeric: tabular-nums;">{{ format_currency($totalActual - $totalTarget) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @if(empty($forPdf))</div>@endif
</div>

{{-- Conclusão Estratégica do Especialista (Vertex AI) — bloco único, sem typewriter --}}
<div class="ai-conclusion">
    <div class="ai-header">
        @if(!($forPdf ?? false))<span style="margin-right: 8px;">&#128737;</span>@endif Conclusão Estratégica do Especialista (Vertex AI)
    </div>
    <div class="ai-body">
        @if($aiConclusionText !== '')
            @php
                $safeConclusion = e($aiConclusionText);
                $safeConclusion = preg_replace('/\*\*Recomendação:\*\*/u', '<strong>Recomendação:</strong>', $safeConclusion);
                $safeConclusion = preg_replace('/Recomendação:\s+/u', '<strong>Recomendação:</strong> ', $safeConclusion);
                $safeConclusion = nl2br($safeConclusion);
            @endphp
            {!! $safeConclusion !!}
        @else
            <p style="margin: 0;">Sua saúde financeira este mês apresenta um Score de <strong>{{ $score }}/100</strong>. Consulte seus pilares 50/30/20 acima e ajuste gastos e investimentos conforme suas metas.</p>
            <p style="margin: 1em 0 0 0;"><strong>Recomendação:</strong> Mantenha o acompanhamento mensal e use as metas e orçamentos da Vertex Contas para consolidar sua disciplina financeira.</p>
        @endif
    </div>
</div>

@if($aiProjection)
<div class="report-block-emerald">
    <div style="font-size: 9pt; font-weight: 600; color: #166534; margin-bottom: 6px;">Projeção para o Próximo Ano</div>
    <p style="margin: 0; color: #166534; font-size: 10pt; font-weight: 500; line-height: 1.6;">{{ $aiProjection }}</p>
</div>
@endif

@if(!empty($aiTips) && count($aiTips) > 0)
@php
    $aiTipsUnique = array_values(array_unique(array_map('trim', $aiTips)));
    if ($aiConclusionText !== '') {
        $aiTipsUnique = array_values(array_filter($aiTipsUnique, fn ($t) => $t !== $aiConclusionText));
    }
    $aiTipsUnique = array_slice($aiTipsUnique, 0, 4);
@endphp
@if(count($aiTipsUnique) > 0)
<div style="margin-bottom: 24px; page-break-inside: avoid;">
    <div style="font-size: 9pt; font-weight: 600; color: #475569; text-transform: uppercase; margin-bottom: 8px;">Dicas do Especialista</div>
    <ul style="margin: 0; padding-left: 20px; font-size: 10pt; line-height: 1.6;">
        @foreach($aiTipsUnique as $tip)
        <li>{{ $tip }}</li>
        @endforeach
    </ul>
</div>
@endif
@endif
@endsection

@section('summary')
<tr>
    <td style="text-align: left;">Renda Total:</td>
    <td style="text-align: right; font-weight: 600;">{{ format_currency($income) }}</td>
</tr>
<tr>
    <td style="text-align: left;">Despesas Totais:</td>
    <td style="text-align: right; font-weight: 600;">{{ format_currency($expenses) }}</td>
</tr>
<tr class="total-row">
    <td style="text-align: left; font-weight: 700;">Fluxo Livre:</td>
    <td style="text-align: right; font-weight: 700; color: #4f46e5;">{{ format_currency($flowFree) }}</td>
</tr>
@endsection
