@extends('core::documents._layout')

@section('documentTitle', 'Fluxo de Caixa')
@section('documentType', 'Fluxo de Caixa')
@section('periodLabel', $periodLabel ?? 'Últimos ' . ($months ?? 6) . ' meses')

@section('content')
<table>
    <thead>
        <tr class="heading-row">
            <th>Mês</th>
            <th class="text-right">Receitas</th>
            <th class="text-right">Despesas</th>
            <th class="text-right">Saldo</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cashFlow ?? [] as $item)
        <tr class="item-row">
            <td>{{ $item['month'] ?? '—' }}</td>
            <td class="text-right">R$ {{ number_format($item['income'] ?? 0, 2, ',', '.') }}</td>
            <td class="text-right">R$ {{ number_format($item['expense'] ?? 0, 2, ',', '.') }}</td>
            <td class="text-right" style="{{ ($item['balance'] ?? 0) >= 0 ? 'color: #059669;' : 'color: #dc2626;' }}">R$ {{ number_format($item['balance'] ?? 0, 2, ',', '.') }}</td>
        </tr>
        @endforeach
        @if(($cashFlow ?? collect())->isNotEmpty())
        <tr class="total-row">
            <td>TOTAL</td>
            <td class="text-right">R$ {{ number_format(collect($cashFlow)->sum('income'), 2, ',', '.') }}</td>
            <td class="text-right">R$ {{ number_format(collect($cashFlow)->sum('expense'), 2, ',', '.') }}</td>
            <td class="text-right" style="{{ collect($cashFlow)->sum('balance') >= 0 ? 'color: #059669;' : 'color: #dc2626;' }}">R$ {{ number_format(collect($cashFlow)->sum('balance'), 2, ',', '.') }}</td>
        </tr>
        @endif
    </tbody>
</table>

@if(($cashFlowByAccount ?? collect())->isNotEmpty())
<h3 style="font-size: 12px; font-weight: 700; color: #334155; margin-top: 24px; margin-bottom: 12px;">Por Conta (Fonte)</h3>
<table>
    <thead>
        <tr class="heading-row">
            <th>Mês</th>
            <th>Conta</th>
            <th class="text-right">Receitas</th>
            <th class="text-right">Despesas</th>
            <th class="text-right">Saldo</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cashFlowByAccount as $row)
        <tr class="item-row">
            <td>{{ $row['month_name'] }}</td>
            <td>{{ $row['account_name'] }}</td>
            <td class="text-right">R$ {{ number_format($row['income'], 2, ',', '.') }}</td>
            <td class="text-right">R$ {{ number_format($row['expense'], 2, ',', '.') }}</td>
            <td class="text-right" style="{{ $row['balance'] >= 0 ? 'color: #059669;' : 'color: #dc2626;' }}">R$ {{ number_format($row['balance'], 2, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if(($topCategories ?? collect())->isNotEmpty())
<h3 style="font-size: 12px; font-weight: 700; color: #334155; margin-top: 24px; margin-bottom: 12px;">Top Categorias de Despesa</h3>
<table>
    <thead>
        <tr class="heading-row">
            <th>Categoria</th>
            <th style="text-align: center;">Transações</th>
            <th class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        @php $totalExp = $topCategories->sum('total'); @endphp
        @foreach($topCategories as $row)
        <tr class="item-row">
            <td>{{ $row['category'] }}</td>
            <td style="text-align: center;">{{ $row['count'] }}</td>
            <td class="text-right">R$ {{ number_format($row['total'], 2, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection
