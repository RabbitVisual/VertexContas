<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('documentTitle', 'Documento')</title>
    <style>
        /* Stripe-inspired enterprise document - A4, 1.27cm margins */
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #1a1f36;
            background: #fff;
        }
        .doc-wrap {
            max-width: 210mm;
            margin: 0 auto;
            padding: 0;
            background: #fff;
        }
        .doc-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }
        .company-logo { max-height: 36px; width: auto; display: block; }
        .doc-title {
            font-size: 18pt;
            font-weight: 600;
            color: #1a1f36;
            margin: 0;
            letter-spacing: -0.02em;
        }
        .doc-meta {
            text-align: right;
            font-size: 9pt;
            color: #697386;
        }
        .doc-meta div { margin-top: 2px; }
        .doc-meta strong { color: #1a1f36; }
        .doc-fromto {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            padding: 20px 0;
            font-size: 10pt;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 24px;
        }
        .doc-fromto-block label {
            display: block;
            font-size: 9pt;
            font-weight: 600;
            color: #697386;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-bottom: 6px;
        }
        .doc-fromto-block .value { color: #1a1f36; line-height: 1.5; }
        .doc-body { padding: 0; }
        .doc-body .section-title {
            font-size: 11pt;
            font-weight: 600;
            color: #1a1f36;
            margin: 24px 0 12px 0;
            letter-spacing: -0.01em;
        }
        .doc-body .section-title:first-child { margin-top: 0; }
        .doc-body table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-bottom: 20px;
        }
        .doc-body th {
            text-align: left;
            font-weight: 600;
            font-size: 9pt;
            color: #697386;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            padding: 10px 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .doc-body th.text-right { text-align: right; }
        .doc-body td {
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            color: #1a1f36;
            font-size: 10pt;
        }
        .doc-body tr:nth-child(even) td { background: #f8fafc; }
        .doc-body td.text-right { text-align: right; font-variant-numeric: tabular-nums; }
        .doc-body .report-block {
            page-break-inside: avoid;
            padding: 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            margin-bottom: 16px;
        }
        .doc-body .report-block-emerald {
            background: #ecfdf5;
            border-color: #a7f3d0;
        }
        .doc-footer {
            margin-top: 32px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
            font-size: 9pt;
            color: #697386;
            text-align: center;
        }
        .doc-actions {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 100;
            transition: opacity 0.3s ease;
        }
        .doc-actions.btn-print-hidden { opacity: 0; pointer-events: none; }
        .doc-actions .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: #635bff;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }
        .doc-actions .btn-print:hover { background: #5851ea; }
        .no-print { display: block; }
        .print-only { display: none !important; }
        .typewriter-cursor { animation: blink 0.8s step-end infinite; }
        @keyframes blink { 50% { opacity: 0; } }
        .score-gauge-wrap, .score-gauge-svg, .score-value, .medal-card, .medal-icon-cell,
        .doc-body .report-block, .doc-body .report-block-emerald, .doc-body table th,
        .doc-body table tr:nth-child(even) td {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        /* A4: 210x297mm, 1.27cm = 12.7mm margins on all sides */
        @page {
            size: A4 portrait;
            margin: 12.7mm;
        }
        @media print {
            html, body {
                background: #fff;
                padding: 0;
                margin: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .doc-wrap {
                max-width: none;
                padding: 0;
                margin: 0;
            }
            .doc-header, .doc-fromto, .doc-body, .doc-footer {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .doc-body th, .doc-body td {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .doc-body table, .doc-body .report-block, .doc-body .page-break-avoid,
            .doc-body .score-gauge-wrap, .doc-body .medal-card {
                page-break-inside: avoid;
            }
            .score-gauge-wrap, .score-gauge-svg, .score-value, .medal-card, .medal-icon-cell,
            .doc-body .report-block, .doc-body .report-block-emerald, .doc-body table th,
            .doc-body table tr:nth-child(even) td {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print { display: none !important; }
            .print-only { display: block !important; }
        }
        @media (max-width: 600px) {
            .doc-fromto { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @hasSection('docActions')
    <div id="btn-print-wrapper" class="no-print doc-actions @yield('docActionsHiddenClass', '')">
        @yield('docActions')
    </div>
    @endif
    <div class="doc-wrap">
        <header class="doc-header">
            <div>
                <img src="{{ $templateData['logo_path'] ?? branding_logo_url('default') ?? asset('storage/logos/logo.svg') }}" alt="Vertex" class="company-logo">
                @hasSection('documentBanner')
                <h1 class="doc-title" style="margin-top: 12px;">@yield('documentBanner')</h1>
                @endif
            </div>
            <div class="doc-meta">
                <div><strong>@yield('documentRightLabel', 'Período'):</strong> @yield('documentRightValue', '—')</div>
                <div><strong>Data:</strong> {{ now()->format('d/m/Y') }}</div>
            </div>
        </header>

        <div class="doc-fromto">
            <div class="doc-fromto-block">
                <label>Emissor</label>
                <div class="value">
                    @if(!empty($templateData['company_address']))<div>{{ $templateData['company_address'] }}</div>@endif
                    @if(!empty($templateData['company_cnpj']))<div>CNPJ: {{ lgpd_format_cnpj($templateData['company_cnpj']) }}</div>@endif
                    @if(!empty($templateData['company_phone']))<div>Tel: {{ lgpd_format_phone($templateData['company_phone']) }}</div>@endif
                    @if(!empty($templateData['company_email']))<div>{{ $templateData['company_email'] }}</div>@endif
                </div>
            </div>
            <div class="doc-fromto-block" style="text-align: right;">
                <label>Cliente</label>
                <div class="value">
                    <div>@yield('clientName', auth()->user()->name ?? '—')</div>
                    <div>@yield('clientEmail', auth()->user()->email ?? '—')</div>
                </div>
            </div>
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
