<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="{{ config('pwa.theme_color', '#11C76F') }}">
    <title>{{ config('pwa.short_name', 'Vertex') }} — Offline</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, -apple-system, sans-serif; background: #f8fafc; color: #1e293b; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem; padding-bottom: max(1.5rem, env(safe-area-inset-bottom)); }
        .card { background: #fff; border-radius: 1rem; padding: 2rem; max-width: 24rem; width: 100%; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        h1 { font-size: 1.25rem; margin: 0 0 0.5rem; }
        p { color: #64748b; font-size: 0.9375rem; margin: 0 0 1.5rem; }
        .btn { display: inline-block; padding: 0.75rem 1.5rem; background: #11C76F; color: #fff; border: none; border-radius: 0.75rem; font-weight: 600; font-size: 0.9375rem; cursor: pointer; text-decoration: none; }
        .btn:hover { background: #0EA85A; }
        .icon { width: 4rem; height: 4rem; margin: 0 auto 1rem; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon" aria-hidden="true">📡</div>
        <h1>Você está offline</h1>
        <p>Verifique sua conexão e tente novamente.</p>
        <button type="button" class="btn" onclick="window.location.reload()">Tentar novamente</button>
    </div>
</body>
</html>
