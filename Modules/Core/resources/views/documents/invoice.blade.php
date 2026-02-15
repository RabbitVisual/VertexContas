@extends('core::documents._layout')

@section('documentTitle', 'Fatura')
@section('documentType', 'Fatura')
@section('periodLabel', $invoice->created_at->format('d/m/Y'))
@section('clientName', $invoice->user->name ?? '—')
@section('clientEmail', $invoice->user->email ?? '—')

@section('content')
@php
    $gatewayLabel = match(strtolower($invoice->gateway_slug ?? '')) {
        'stripe' => 'Cartão (Stripe)',
        'mercadopago' => 'Mercado Pago',
        default => $invoice->gateway_slug ? ucfirst($invoice->gateway_slug) : '—',
    };
    $currency = $invoice->currency ?? 'BRL';
    $amountFormatted = 'R$ ' . number_format((float) $invoice->amount, 2, ',', '.');
@endphp
<table>
    <tr class="heading-row">
        <td>Descrição</td>
        <td>Valor</td>
    </tr>
    <tr class="item-row">
        <td>Assinatura Vertex PRO Mensal</td>
        <td>{{ $amountFormatted }}</td>
    </tr>
    <tr class="item-row">
        <td>Método de pagamento</td>
        <td>{{ $gatewayLabel }}</td>
    </tr>
    <tr class="item-row">
        <td>Status</td>
        <td>{{ $invoice->status === 'succeeded' ? 'Pago' : ucfirst($invoice->status) }}</td>
    </tr>
    <tr class="total-row">
        <td>Total</td>
        <td>{{ $amountFormatted }}</td>
    </tr>
</table>
@endsection
