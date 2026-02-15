@extends('core::documents._layout')

@section('documentTitle', 'Extrato Vertex')
@section('documentRightValue', $periodLabel ?? '')

@section('content')
<table>
    <thead>
        <tr class="heading-row">
            <th>Data</th>
            <th>Descrição</th>
            <th>Categoria</th>
            <th>Conta</th>
            <th class="text-right">Crédito</th>
            <th class="text-right">Débito</th>
            <th class="text-right">Saldo</th>
        </tr>
    </thead>
    <tbody>
        @foreach($statement ?? [] as $item)
        <tr class="item-row">
            <td>{{ $item['transaction']->date->format('d/m/Y') }}</td>
            <td>{{ $item['transaction']->description ?? '—' }}</td>
            <td>{{ $item['transaction']->category?->name ?? '—' }}</td>
            <td>{{ $item['transaction']->account?->name ?? '—' }}</td>
            <td class="text-right">{{ $item['credit'] > 0 ? format_currency($item['credit']) : '—' }}</td>
            <td class="text-right">{{ $item['debit'] > 0 ? format_currency($item['debit']) : '—' }}</td>
            <td class="text-right" style="{{ ($item['balance'] ?? 0) >= 0 ? 'color: #059669;' : 'color: #dc2626;' }}">{{ format_currency($item['balance'] ?? 0) }}</td>
        </tr>
        @endforeach
        @if(!empty($totals))
        <tr class="total-row">
            <td colspan="4">TOTAL</td>
            <td class="text-right">{{ format_currency($totals['total_credit'] ?? 0) }}</td>
            <td class="text-right">{{ format_currency($totals['total_debit'] ?? 0) }}</td>
            <td class="text-right">{{ format_currency($totals['final_balance'] ?? 0) }}</td>
        </tr>
        @endif
    </tbody>
</table>
@endsection
