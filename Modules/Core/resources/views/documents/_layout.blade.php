<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('documentTitle', 'Documento') - {{ $templateData['company_name'] ?? 'Vertex Contas' }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; border-width: 0; border-style: solid; border-color: #e2e8f0; }
        body {
            margin: 0;
            padding: 20px 0;
            font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.45;
            color: #334155;
            background: #e5e7eb;
        }
        .doc-wrap {
            max-width: 210mm;
            margin: 0 auto;
            padding: 0 56px;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        /* Header: logo + meta (Templid-style) */
        .doc-header {
            padding: 32px 0 24px;
            border-bottom: 1px solid #e2e8f0;
        }
        .doc-header-inner {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .doc-header-inner td { vertical-align: top; padding: 0; }
        .doc-header-inner .col-meta {
            text-align: right;
            white-space: nowrap;
        }
        .company-logo { max-height: 40px; width: auto; display: block; }
        .company-name {
            font-size: 11px;
            font-weight: 600;
            color: #1e293b;
            margin-top: 6px;
        }
        .doc-meta-label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 2px;
        }
        .doc-meta-value {
            font-size: 11px;
            font-weight: 600;
            color: #1e293b;
        }
        .doc-meta-cell { padding-left: 24px; }
        .doc-meta-cell.border-r { border-right: 1px solid #e2e8f0; padding-right: 24px; }
        /* From/To */
        .doc-fromto {
            background: #f8fafc;
            padding: 20px 0;
            font-size: 11px;
            border-bottom: 1px solid #e2e8f0;
        }
        .doc-fromto-inner { display: table; width: 100%; border-collapse: collapse; }
        .doc-fromto-inner td { vertical-align: top; width: 50%; padding: 0; }
        .doc-fromto .from-label,
        .doc-fromto .to-label {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .doc-fromto .from-meta,
        .doc-fromto .to-meta {
            color: #475569;
            line-height: 1.5;
        }
        .doc-fromto .to-block { text-align: right; }
        /* Body + tables */
        .doc-body { padding: 28px 0 32px; }
        .doc-body table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 24px;
        }
        .doc-body table:last-child { margin-bottom: 0; }
        .doc-body td, .doc-body th {
            padding: 10px 12px;
            vertical-align: middle;
            text-align: left;
        }
        .doc-body .heading-row th,
        .doc-body .heading-row td {
            background: #334155;
            color: #fff;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border: none;
            padding: 10px 12px;
        }
        .doc-body .heading-row th.text-right,
        .doc-body .heading-row td.text-right { text-align: right; }
        .doc-body .item-row td {
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }
        .doc-body .item-row:nth-child(even) td { background: #fafafa; }
        .doc-body .item-row td.text-right { text-align: right; font-variant-numeric: tabular-nums; }
        .doc-body .total-row td {
            border-top: 2px solid #334155;
            background: #334155 !important;
            color: #fff !important;
            font-weight: 700;
            padding: 10px 12px;
        }
        .doc-body .total-row td.text-right { text-align: right; font-variant-numeric: tabular-nums; }
        .doc-body .section-title {
            font-size: 11px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 28px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .doc-body .section-title:first-child { margin-top: 0; }
        /* Footer */
        .doc-footer {
            padding: 16px 0 24px;
            border-top: 1px solid #e2e8f0;
            font-size: 10px;
            color: #94a3b8;
            text-align: center;
        }
        .no-print { display: block; }
        /* Print */
        @page {
            size: A4 portrait;
            margin: 15mm;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
                margin: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .doc-wrap {
                max-width: none;
                padding: 0 56px;
                box-shadow: none;
            }
            .doc-header, .doc-fromto, .doc-body, .doc-footer {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .doc-body .heading-row th,
            .doc-body .heading-row td,
            .doc-body .total-row td {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .doc-body table { page-break-inside: avoid; }
            .no-print { display: none !important; }
        }
        @media (max-width: 640px) {
            .doc-wrap { padding: 0 24px; }
            .doc-fromto-inner td { display: block; width: 100%; }
            .doc-fromto .to-block { text-align: left; margin-top: 12px; }
        }
    </style>
</head>
<body>
    <div class="doc-wrap">
        <header class="doc-header">
            <table class="doc-header-inner">
                <tr>
                    <td style="width: 100%;">
                        <img src="{{ $templateData['logo_path'] ?? asset('storage/logos/logo.svg') }}" alt="Vertex" class="company-logo">
                        <div class="company-name">{{ $templateData['company_name'] ?? 'Vertex Contas' }}</div>
                    </td>
                    <td style="vertical-align: top; text-align: right; white-space: nowrap;">
                        <table style="display: inline-table; border-collapse: collapse; text-align: right;">
                            <tr>
                                <td class="doc-meta-cell border-r">
                                    <div class="doc-meta-label">Data</div>
                                    <div class="doc-meta-value">{{ now()->format('d/m/Y') }}</div>
                                </td>
                                <td class="doc-meta-cell">
                                    <div class="doc-meta-label">@yield('documentRightLabel', 'Período')</div>
                                    <div class="doc-meta-value">@yield('documentRightValue', '—')</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </header>

        <div class="doc-fromto">
            <table class="doc-fromto-inner">
                <tr>
                    <td>
                        <div class="from-label">{{ $templateData['company_name'] ?? 'Vertex Contas' }}</div>
                        <div class="from-meta">
                            @if(!empty($templateData['company_address']))<div>{{ $templateData['company_address'] }}</div>@endif
                            @if(!empty($templateData['company_cnpj']))<div>CNPJ: {{ lgpd_format_cnpj($templateData['company_cnpj']) }}</div>@endif
                            @if(!empty($templateData['company_phone']))<div>Tel: {{ lgpd_format_phone($templateData['company_phone']) }}</div>@endif
                            @if(!empty($templateData['company_email']))<div>{{ $templateData['company_email'] }}</div>@endif
                        </div>
                    </td>
                    <td>
                        <div class="to-block">
                            <div class="to-label">Cliente</div>
                            <div class="to-meta">
                                <div>@yield('clientName', auth()->user()->name ?? '—')</div>
                                <div>@yield('clientEmail', auth()->user()->email ?? '—')</div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="doc-body">
            @yield('content')
        </div>

        <footer class="doc-footer">
            {{ $templateData['document_footer_text'] ?? 'Vertex Contas - Sistema de Gestão Financeira' }}
        </footer>
    </div>
</body>
</html>
