@extends('core::documents._layout')

@section('documentTitle', 'Ranking de Categorias')
@section('documentType', 'Ranking de Categorias')
@section('periodLabel', $periodLabel ?? '')

@section('content')
@php
    $totalDespesas = ($ranking ?? collect())->sum('total');
    $rankingWithPercent = collect($ranking ?? [])->map(function ($item) use ($totalDespesas) {
        $pct = $totalDespesas > 0 ? round(($item['total'] / $totalDespesas) * 100, 1) : 0;
        return array_merge($item, ['percent' => $pct]);
    });
@endphp

{{-- Resumo visual --}}
@if(!empty($summary))
<div style="display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
    <div style="flex: 1; min-width: 120px; padding: 12px 16px; background: #f0fdfa; border: 1px solid #99f6e4; border-radius: 8px;">
        <div style="font-size: 10px; font-weight: 700; color: #0d9488; text-transform: uppercase; letter-spacing: 0.05em;">Receitas</div>
        <div style="font-size: 18px; font-weight: 800; color: #0f766e;">R$ {{ number_format($summary['income'] ?? 0, 2, ',', '.') }}</div>
    </div>
    <div style="flex: 1; min-width: 120px; padding: 12px 16px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px;">
        <div style="font-size: 10px; font-weight: 700; color: #dc2626; text-transform: uppercase; letter-spacing: 0.05em;">Despesas</div>
        <div style="font-size: 18px; font-weight: 800; color: #b91c1c;">R$ {{ number_format($summary['expense'] ?? 0, 2, ',', '.') }}</div>
    </div>
    <div style="flex: 1; min-width: 120px; padding: 12px 16px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px;">
        <div style="font-size: 10px; font-weight: 700; color: #2563eb; text-transform: uppercase; letter-spacing: 0.05em;">Taxa de Poupança</div>
        <div style="font-size: 18px; font-weight: 800; color: #1d4ed8;">{{ number_format($summary['savings_rate'] ?? 0, 1) }}%</div>
    </div>
</div>
@endif

{{-- Gráfico de barras (CSS) - imprime perfeitamente --}}
@if($rankingWithPercent->isNotEmpty())
<h3 style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 12px; text-transform: uppercase;">Distribuição de Despesas por Categoria</h3>
<div style="margin-bottom: 24px;">
    @foreach($rankingWithPercent as $item)
    <div style="margin-bottom: 10px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 3px; font-size: 11px;">
            <span style="font-weight: 600; color: #1e293b;">{{ $item['category'] }}</span>
            <span style="color: #64748b;">{{ $item['percent'] }}% · R$ {{ number_format($item['total'], 2, ',', '.') }}</span>
        </div>
        <div style="height: 10px; background: #e2e8f0; border-radius: 4px; overflow: hidden;">
            <div style="height: 100%; width: {{ min($item['percent'], 100) }}%; background: {{ $item['color'] ?? '#0d9488' }}; border-radius: 4px;"></div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Tabela detalhada --}}
<h3 style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 12px; text-transform: uppercase;">Ranking Detalhado</h3>
<table>
    <thead>
        <tr class="heading-row">
            <th>Categoria</th>
            <th style="text-align: center;">Transações</th>
            <th class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ranking ?? [] as $item)
        <tr class="item-row">
            <td>
                <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: {{ $item['color'] ?? '#64748b' }}; margin-right: 8px; vertical-align: middle;"></span>
                {{ $item['category'] ?? '—' }}
            </td>
            <td style="text-align: center;">{{ $item['count'] ?? 0 }}</td>
            <td class="text-right">R$ {{ number_format($item['total'] ?? 0, 2, ',', '.') }}</td>
        </tr>
        @endforeach
        @if(($ranking ?? collect())->isNotEmpty())
        <tr class="total-row">
            <td>TOTAL</td>
            <td style="text-align: center;">{{ $ranking->sum('count') }}</td>
            <td class="text-right">R$ {{ number_format($ranking->sum('total'), 2, ',', '.') }}</td>
        </tr>
        @endif
    </tbody>
</table>
@endsection
