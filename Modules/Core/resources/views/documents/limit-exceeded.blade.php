<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0f172a">
    <title>Limite Atingido - {{ config('app.name', 'Vertex Contas') }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: 16px; -webkit-text-size-adjust: 100%; }
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: #f8fafc;
            color: #334155;
            line-height: 1.6;
        }

        .container {
            width: 100%;
            max-width: 420px;
        }

        /* Logo */
        .logo-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .logo {
            height: 36px;
            width: auto;
        }

        /* Card - HyperUI style: bordered, clean */
        .card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        .card-body {
            padding: 2rem;
        }

        /* Ícone centralizado, discreto */
        .icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 1.5rem;
            background: #fef3c7;
            border: 1px solid #fde68a;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .badge {
            display: inline-block;
            font-size: 0.6875rem;
            font-weight: 600;
            color: #059669;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.75rem;
        }

        h1 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .message {
            font-size: 0.9375rem;
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        /* Box informativo - borda simples */
        .info {
            padding: 1rem 1.25rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.8125rem;
            color: #475569;
            margin-bottom: 1rem;
            line-height: 1.6;
        }
        .info strong { color: #1e293b; }

        .reset {
            font-size: 0.8125rem;
            color: #059669;
            margin-bottom: 1.5rem;
        }

        .note {
            font-size: 0.75rem;
            color: #94a3b8;
            line-height: 1.5;
        }

        /* Botão - clean, bordered */
        .btn {
            display: block;
            width: 100%;
            margin-top: 1.5rem;
            padding: 0.75rem 1.25rem;
            background: #0f172a;
            color: #fff;
            font-size: 0.9375rem;
            font-weight: 500;
            text-align: center;
            text-decoration: none;
            border: 1px solid #0f172a;
            border-radius: 8px;
            transition: background 0.15s, color 0.15s;
        }
        .btn:hover {
            background: #1e293b;
        }

        @media (max-width: 480px) {
            body { padding: 1rem; }
            .card-body { padding: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="logo-wrap">
            <img src="{{ branding_logo_url('default') }}" alt="{{ config('app.name') }}" class="logo" width="168" height="36">
        </header>

        <main class="card">
            <div class="card-body">
                <div class="icon" aria-hidden="true">&#128337;</div>
                <span class="badge">{{ plan_pro_name() }}</span>
                <h1>Limite atingido</h1>
                <p class="message">{{ $message ?? 'Você atingiu o limite para este recurso.' }}</p>

                <div class="info">
                    <strong>Por que?</strong> Limitamos relatórios e projeções por IA para proteger seus dados e otimizar o uso. Medida de segurança para todos os assinantes PRO.
                </div>

                @if(!empty($resets_note))
                <p class="reset">{{ $resets_note }}</p>
                @endif

                <p class="note">Seu acesso ao {{ plan_pro_name() }} permanece completo. O limite aplica-se apenas à impressão/visualização de documentos. Dúvidas: suporte.</p>

                <a href="{{ url()->previous() ?: route('core.reports.index') }}" class="btn">Voltar</a>
            </div>
        </main>
    </div>
</body>
</html>
