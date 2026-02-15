@extends('core::documents._layout')

@section('documentTitle', 'Fatura')
@section('clientName', $invoice->user->name ?? '—')
@section('clientEmail', $invoice->user->email ?? '—')
@section('documentRightLabel', 'Nº')
@section('documentRightValue', $invoice->external_id ?? 'FAT-' . $invoice->id)

@section('content')
@php
    $gatewayLabel = match(strtolower($invoice->gateway_slug ?? '')) {
        'stripe' => 'Cartão (Stripe)',
        'mercadopago' => 'Mercado Pago',
        default => $invoice->gateway_slug ? ucfirst($invoice->gateway_slug) : '—',
    };
    $amountFormatted = format_currency((float) $invoice->amount);
@endphp
<table>
    <thead>
        <tr class="heading-row">
            <td>Descrição</td>
            <td class="text-right">Valor</td>
        </tr>
    </thead>
    <tbody>
        <tr class="item-row">
            <td>Assinatura {{ plan_pro_name() }} Mensal</td>
            <td class="text-right">{{ $amountFormatted }}</td>
        </tr>
        <tr class="item-row">
            <td>Método de pagamento</td>
            <td class="text-right">{{ $gatewayLabel }}</td>
        </tr>
        <tr class="item-row">
            <td>Status</td>
            <td class="text-right">{{ $invoice->status === 'succeeded' ? 'Pago' : ucfirst($invoice->status) }}</td>
        </tr>
        <tr class="total-row">
            <td>Total</td>
            <td class="text-right">{{ $amountFormatted }}</td>
        </tr>
    </tbody>
</table>
@endsection
