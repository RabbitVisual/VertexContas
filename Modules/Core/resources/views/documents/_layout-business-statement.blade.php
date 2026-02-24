{{--
    Vertex Business Statement (A4 Optimized) - Layout oficial do relatório de consultoria.
    Estrutura 100% em <table> HTML com table-layout: fixed para PDF estável (DomPDF/Hostinger).
    Todo o CSS está embutido em <style>; logo em Base64 para evitar falhas de SSL/path.
    Na tela: layout responsivo com container legível; no PDF: A4 portrait.
--}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('documentTitle', 'Relatório PRO - Vertex Contas')</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm;
        }

        @media print {
            body { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            .no-print { display: none !important; }
            .doc-screen-only { display: none !important; }
            .doc-viewport { max-width: none !important; padding: 0 !important; box-shadow: none !important; }
        }

        body {
            font-family: 'Inter', 'Arial', sans-serif;
            font-size: 10pt;
            color: #0f172a;
            margin: 0;
            padding: 0;
            line-height: 1.5;
            width: 100%;
            background-color: #f1f5f9;
        }
        body.doc-pdf {
            background-color: #fff !important;
        }

        .doc-viewport {
            max-width: 210mm;
            margin: 0 auto;
            padding: 24px 16px 48px;
            min-height: 100vh;
            box-sizing: border-box;
            background: #fff;
        }
        @media (min-width: 768px) {
            .doc-viewport {
                padding: 32px 24px 64px;
                box-shadow: 0 4px 24px rgba(15, 23, 42, 0.08);
                margin-top: 24px;
                margin-bottom: 24px;
            }
        }

        .page-wrapper {
            width: 100%;
        }
        .page-wrapper td {
            padding: 0;
            vertical-align: top;
        }
        .content-cell {
            width: 100%;
            padding: 0;
        }

        .table-scroll-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-left: -12px;
            margin-right: -12px;
            padding: 0 12px;
        }
        .table-scroll-wrap .bs-table-pillar { min-width: 560px; }
        @media (min-width: 640px) {
            .table-scroll-wrap { margin-left: 0; margin-right: 0; padding: 0; }
        }

        .layout-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }
        .layout-table.header-table { border-bottom: 2px solid #e2e8f0; }
        .layout-table.header-table td { vertical-align: top; padding: 0 0 20px 0; }
        .layout-table.header-table td:first-child { width: 50%; }
        .layout-table.header-table td:last-child { width: 50%; text-align: right; }
        .layout-table.meta-table { margin-bottom: 30px; }
        .layout-table.meta-table td { vertical-align: top; padding-top: 16px; font-size: 10pt; }
        .layout-table.meta-table .meta-label { font-size: 8pt; font-weight: 600; color: #475569; text-transform: uppercase; }
        .layout-table.meta-table .meta-value { font-size: 10pt; font-weight: 500; }
        .logo { height: 45px; display: block; }
        .doc-meta-line { font-size: 10pt; margin: 2px 0; }
        .doc-meta-line strong { color: #4f46e5; }

        .meta-label { font-size: 8pt; font-weight: 600; color: #475569; text-transform: uppercase; }
        .meta-value { font-size: 10pt; font-weight: 500; }

        .bs-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 30px;
            font-family: 'Inter', 'Arial', sans-serif;
            font-size: 10pt;
        }
        .bs-table th {
            background-color: #f8fafc;
            text-align: left;
            padding: 12px;
            font-size: 8pt;
            font-weight: 600;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
            text-transform: uppercase;
        }
        .bs-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
            font-size: 10pt;
        }
        .pillar-table-wrap { margin-bottom: 30px; page-break-inside: avoid; }
        .pillar-table-title {
            font-size: 9pt;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            margin-bottom: 10px;
        }
        .bs-table-pillar {
            border: 1px solid #e2e8f0;
        }
        .bs-table-pillar th,
        .bs-table-pillar td {
            border: 1px solid #e2e8f0;
            padding: 10px 12px;
        }
        .bs-table-pillar th {
            background-color: #f1f5f9;
        }
        .pillar-total-row td {
            background-color: #f8fafc;
            border-top: 2px solid #0f172a;
            font-weight: 600;
        }
        .row-zebra:nth-child(even) { background-color: #fafafa; }
        .row-zebra:nth-child(even) td { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .text-right { text-align: right; }
        .font-bold { font-weight: 700; }
        .pilar-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: 600;
        }

        .summary-outer { width: 100%; margin-bottom: 50px; }
        .summary-outer td { text-align: right; vertical-align: top; }
        .summary-inner { width: 250px; margin-left: auto; table-layout: fixed; border-collapse: collapse; }
        .summary-inner td { padding: 5px 0; font-size: 10pt; }
        .summary-inner .total-row td { border-top: 2px solid #0f172a; padding-top: 10px; margin-top: 10px; font-size: 12pt; }

        .ai-conclusion {
            background-color: #f8fafc;
            border-left: 4px solid #4f46e5;
            padding: 15px;
            margin-bottom: 40px;
            page-break-inside: avoid;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .ai-conclusion .ai-body { text-align: justify; line-height: 1.6; margin: 0; font-size: 10pt; }
        .ai-header {
            margin-bottom: 10px;
            color: #4f46e5;
            font-weight: 700;
            font-size: 10pt;
            text-transform: uppercase;
        }

        .footer {
            margin-top: 50px;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
            font-size: 8pt;
            color: #475569;
            text-align: center;
        }

        .doc-actions {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 100;
        }
        .doc-actions .btn-print {
            display: inline-block;
            padding: 10px 18px;
            background: #4f46e5;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            margin-left: 8px;
        }
        .doc-actions .btn-print:first-child { margin-left: 0; }
        .doc-actions .btn-print:hover { opacity: 0.9; }
        .report-block-emerald {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 0 8px 8px 0;
            padding: 16px;
            margin-bottom: 24px;
            page-break-inside: avoid;
        }
    </style>
</head>
<body @if(!empty($forPdf)) class="doc-pdf" @endif>
    @if(empty($forPdf))
        @hasSection('docActions')
        <div class="no-print doc-screen-only doc-actions">
            @yield('docActions')
        </div>
        @endif
    @endif

    @if(empty($forPdf))<div class="doc-viewport">@endif
    <table class="page-wrapper layout-table" role="presentation">
        <tr>
            <td class="content-cell">
                <table class="layout-table header-table">
                    <tr>
                        <td>
                            @php $logoBase64 = branding_logo_base64('default'); @endphp
                            @if($logoBase64 !== '')
                                <img src="{{ $logoBase64 }}" alt="" class="logo" role="presentation">
                            @else
                                &nbsp;
                            @endif
                        </td>
                        <td>
                            <div class="doc-meta-line"><strong>Ref:</strong> @yield('documentRef', '#CONS-' . now()->format('Y-m'))</div>
                            <div class="doc-meta-line">Data: @yield('emissionDate', now()->locale('pt_BR')->translatedFormat('d \d\e F, Y'))</div>
                            <div class="doc-meta-line">Período: @yield('periodLabel', now()->locale('pt_BR')->translatedFormat('F Y'))</div>
                        </td>
                    </tr>
                </table>

                <table class="layout-table meta-table">
                    <tr>
                        <td style="width: 50%;">
                            <div class="meta-label">Para:</div>
                            <div class="meta-value"><strong>@yield('clientName', auth()->user()->name ?? '—')</strong></div>
                            <div class="meta-value" style="color: #64748b;">@yield('clientPlan', 'Plano Vertex PRO')</div>
                        </td>
                        <td style="width: 50%;"></td>
                    </tr>
                </table>

                @yield('content')

                @hasSection('summary')
                <table class="layout-table summary-outer">
                    <tr>
                        <td>
                            <table class="summary-inner">
                                @yield('summary')
                            </table>
                        </td>
                    </tr>
                </table>
                @endif

                <table class="layout-table" style="margin-top: 50px;">
                    <tr>
                        <td class="footer">
                            {!! $templateData['document_footer_text'] ?? 'Este documento é uma análise estratégica baseada na metodologia Vertex de Gestão Financeira.<br><strong>Vertex Solutions LTDA. - Inteligência Financeira de Elite.</strong><br>Documento gerado em conformidade com a LGPD e políticas de segurança Vertex.' !!}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    @if(empty($forPdf))</div>@endif
</body>
</html>
