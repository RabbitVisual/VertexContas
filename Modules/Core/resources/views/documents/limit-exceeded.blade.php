<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Pausa para hoje - Vertex Contas</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: 16px; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(16px, 5vw, 32px);
            color: #334155;
            overflow-x: hidden;
        }

        /* Fundo animado - tecnologia e segurança */
        .bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: linear-gradient(135deg, #f0fdfa 0%, #e0f2fe 50%, #f8fafc 100%);
        }
        .bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(13, 148, 136, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(13, 148, 136, 0.04) 1px, transparent 1px);
            background-size: 40px 40px;
            animation: gridMove 20s linear infinite;
        }
        .bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 80% 50% at 20% 40%, rgba(13, 148, 136, 0.08) 0%, transparent 50%),
                        radial-gradient(ellipse 60% 40% at 80% 60%, rgba(14, 165, 233, 0.06) 0%, transparent 50%);
            animation: pulse 8s ease-in-out infinite alternate;
        }
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.5;
            animation: float 12s ease-in-out infinite;
        }
        .orb-1 { width: 300px; height: 300px; background: rgba(13, 148, 136, 0.15); top: -10%; left: -5%; animation-delay: 0s; }
        .orb-2 { width: 250px; height: 250px; background: rgba(14, 165, 233, 0.12); bottom: -5%; right: -5%; animation-delay: -4s; }
        .orb-3 { width: 180px; height: 180px; background: rgba(16, 185, 129, 0.1); top: 50%; left: 50%; animation-delay: -6s; }
        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(40px, 40px); }
        }
        @keyframes pulse {
            0% { opacity: 0.8; }
            100% { opacity: 1; }
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(20px, -15px) scale(1.02); }
            66% { transform: translate(-15px, 10px) scale(0.98); }
        }

        /* Card */
        .card {
            position: relative;
            z-index: 1;
            max-width: 440px;
            width: 100%;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: clamp(28px, 6vw, 44px) clamp(24px, 5vw, 40px);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(15, 23, 42, 0.08),
                        0 0 0 1px rgba(255, 255, 255, 0.5) inset;
            text-align: left;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
            border: 1px solid rgba(13, 148, 136, 0.3);
            border-radius: 100px;
            font-size: 0.6875rem;
            font-weight: 700;
            color: #0f766e;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
        }
        .icon-wrap {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.25rem;
        }
        h1 {
            font-size: clamp(1.25rem, 4vw, 1.375rem);
            font-weight: 800;
            color: #0f172a;
            line-height: 1.3;
            margin-bottom: 0.5rem;
        }
        .lead {
            font-size: clamp(0.875rem, 2.5vw, 0.9375rem);
            color: #475569;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        .reason {
            font-size: clamp(0.8125rem, 2.2vw, 0.875rem);
            color: #64748b;
            line-height: 1.7;
            padding: 1rem 1.25rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 14px;
            margin-bottom: 1.25rem;
            border-left: 4px solid #0d9488;
        }
        .reason strong { color: #334155; }
        .reset {
            font-size: clamp(0.8125rem, 2.2vw, 0.875rem);
            color: #0d9488;
            font-weight: 600;
        }
        .reset::before {
            content: '✓';
            display: inline-block;
            margin-right: 0.5rem;
            color: #059669;
        }
        .pro-note {
            font-size: clamp(0.6875rem, 1.8vw, 0.75rem);
            color: #94a3b8;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid rgba(226, 232, 240, 0.8);
            line-height: 1.6;
        }

        @media (max-width: 480px) {
            .card { padding: 24px 20px; }
            .reason { padding: 14px 16px; }
        }
    </style>
</head>
<body>
    <div class="bg">
        <div class="orb orb-1" aria-hidden="true"></div>
        <div class="orb orb-2" aria-hidden="true"></div>
        <div class="orb orb-3" aria-hidden="true"></div>
    </div>
    <div class="card">
        <div class="badge">Vertex Pro</div>
        <div class="icon-wrap">&#128337;</div>
        <h1>Pausa para hoje</h1>
        <p class="lead">{{ $message ?? 'Você atingiu o limite diário para este recurso.' }}</p>

        <div class="reason">
            <strong>Por que essa mensagem?</strong><br>
            Para proteger seus dados financeiros, limitamos quantas vezes por dia é possível abrir relatórios e documentos para impressão. É uma medida de segurança que aplicamos a todos os assinantes.
        </div>

        <p class="reset">Seu limite será renovado automaticamente amanhã.</p>

        <p class="pro-note">
            Você continua com acesso completo ao Vertex Pro. Esse limite é apenas para impressão/visualização de documentos. Os dados seguem disponíveis na tela principal.
        </p>
    </div>
</body>
</html>
