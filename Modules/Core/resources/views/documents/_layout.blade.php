<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('documentTitle', 'Documento') - {{ $templateData['company_name'] ?? 'Vertex Contas' }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 20px;
            font-family: 'Segoe UI', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #1e293b;
            background: #f1f5f9;
        }
        .document-box {
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 0;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        /* Header estilo utility bill */
        .doc-header {
            padding: 24px 32px 20px;
            background: #fff;
            border-bottom: 4px solid #0d9488;
        }
        .doc-header-inner {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
        }
        .company-block {
            flex: 1;
            min-width: 200px;
        }
        .company-logo { max-height: 44px; width: auto; display: block; }
        .brand-pro {
            font-size: 18px;
            font-weight: 800;
            color: #0d9488;
            margin-top: 10px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            display: inline-block;
            padding: 4px 12px;
            background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
            border-radius: 6px;
            border: 1px solid #99f6e4;
        }
        .company-meta {
            font-size: 11px;
            color: #64748b;
            line-height: 1.5;
            margin-top: 4px;
        }
        .client-block {
            text-align: right;
            min-width: 180px;
        }
        .client-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 4px;
        }
        .client-name { font-weight: 700; color: #0f172a; }
        .client-email { font-size: 11px; color: #475569; }
        /* Bloco de período / tipo de documento */
        .doc-info-bar {
            padding: 12px 32px;
            background: #f0fdfa;
            border-bottom: 1px solid #99f6e4;
        }
        .doc-info-bar table { width: 100%; }
        .doc-info-bar td { padding: 2px 0; font-size: 11px; }
        .doc-info-bar .doc-type { font-weight: 800; color: #0f766e; font-size: 14px; }
        /* Conteúdo principal */
        .doc-body { padding: 24px 32px 32px; }
        .doc-body table { width: 100%; border-collapse: collapse; font-size: 11px; }
        .doc-body table td, .doc-body table th { padding: 8px 10px; vertical-align: middle; }
        .doc-body .heading-row th,
        .doc-body .heading-row td {
            background: #0d9488;
            color: #fff;
            font-weight: 700;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border: none;
        }
        .doc-body .heading-row th:last-child,
        .doc-body .heading-row th.text-right { text-align: right; }
        .doc-body .item-row td { border-bottom: 1px solid #e2e8f0; }
        .doc-body .item-row:nth-child(even) td { background: #f8fafc; }
        .doc-body .item-row td.text-right { text-align: right; font-variant-numeric: tabular-nums; }
        .doc-body .total-row td {
            border-top: 2px solid #0d9488;
            padding-top: 12px;
            font-weight: 800;
            background: #fff !important;
        }
        .doc-body .total-row td.text-right { text-align: right; font-variant-numeric: tabular-nums; }
        /* Rodapé */
        .doc-footer {
            padding: 16px 32px 24px;
            border-top: 1px solid #e2e8f0;
            font-size: 10px;
            color: #94a3b8;
            text-align: center;
        }
        .no-print { display: block; }
        @media print {
            body { background: #fff; padding: 0; margin: 0; }
            .document-box {
                max-width: none;
                min-height: auto;
                box-shadow: none;
                padding: 0;
            }
            .no-print { display: none !important; }
            @page {
                size: A4 portrait;
                margin: 12mm;
            }
        }
        @media (max-width: 600px) {
            .doc-header-inner { flex-direction: column; }
            .client-block { text-align: left; }
        }
    </style>
</head>
<body>
    <div class="document-box">
        <div class="doc-header">
            <div class="doc-header-inner">
                <div class="company-block">
                    <img src="{{ $templateData['logo_path'] ?? asset('storage/logos/logo.svg') }}" alt="Vertex" class="company-logo">
                    <div class="brand-pro">@yield('brandLabel', 'Vertex Pro')</div>
                    @if(!empty($templateData['company_address']) || !empty($templateData['company_cnpj']) || !empty($templateData['company_phone']) || !empty($templateData['company_email']))
                    <div class="company-meta">
                        @if(!empty($templateData['company_address']))<div>{{ $templateData['company_address'] }}</div>@endif
                        @if(!empty($templateData['company_cnpj']))<div>CNPJ: {{ $templateData['company_cnpj'] }}</div>@endif
                        @if(!empty($templateData['company_phone']))<div>Tel: {{ $templateData['company_phone'] }}</div>@endif
                        @if(!empty($templateData['company_email']))<div>{{ $templateData['company_email'] }}</div>@endif
                    </div>
                    @endif
                </div>
                <div class="client-block">
                    <div class="client-label">Cliente</div>
                    <div class="client-name">@yield('clientName', auth()->user()->name ?? '—')</div>
                    <div class="client-email">@yield('clientEmail', auth()->user()->email ?? '—')</div>
                </div>
            </div>
        </div>

        <div class="doc-info-bar">
            <table>
                <tr>
                    <td><span class="doc-type">@yield('documentType', 'Documento')</span></td>
                    <td style="text-align: right;">Período: @yield('periodLabel', '—')</td>
                </tr>
                <tr>
                    <td>Gerado em: {{ now()->format('d/m/Y H:i') }}</td>
                    <td></td>
                </tr>
            </table>
        </div>

        <div class="doc-body">
            @yield('content')
        </div>

        <div class="doc-footer">
            {{ $templateData['document_footer_text'] ?? 'Vertex Contas - Sistema de Gestão Financeira' }}
        </div>
    </div>
</body>
</html>
