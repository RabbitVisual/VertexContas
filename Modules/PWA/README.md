# Módulo PWA — Vertex Contas

Progressive Web App: permite instalar o Vertex Contas como aplicativo no celular e desktop (atalho na tela inicial, modo standalone).

## Recursos

- **Manifest** (`/manifest.webmanifest`): nome, ícones (SVG + opcional PNG maskable), tema, atalhos (Dashboard, Nova transação), categoria `finance`.
- **Service Worker** (`/sw.js`): cache de assets estáticos (cache-first), navegação network-first com fallback offline, API não cacheada, página offline em `/pwa/offline`, suporte a `SKIP_WAITING` para atualização forçada.
- **Banner de instalação**: no layout do painel do usuário (PanelUser), com suporte a `beforeinstallprompt` (Android/Chrome) e instruções para iOS (Compartilhar → Adicionar à Tela de Início).
- **Responsividade e safe-area**: viewport `viewport-fit=cover`, uso de `env(safe-area-inset-*)` no banner, navbar e sidebar para dispositivos com notch/home indicator.

## Configuração (.env)

| Variável | Descrição | Padrão |
|----------|-----------|--------|
| `PWA_ENABLED` | Ativa manifest, SW e banner | `true` |
| `PWA_SW_REGISTRATION` | Registrar Service Worker no cliente | `true` |
| `PWA_THEME_COLOR` | Cor da barra de status (manifest e meta) | `#11C76F` |
| `PWA_BACKGROUND_COLOR` | Cor de fundo do splash | `#ffffff` |
| `PWA_CACHE_VERSION` | Versão do cache (invalida caches antigos ao alterar) | `v1` |
| `PWA_APP_NAME` | Nome completo no manifest | `APP_NAME` |
| `PWA_SHORT_NAME` | Nome curto (tela inicial) | `Vertex` |
| `PWA_START_URL` | URL de abertura ao abrir o app | `/user` |
| `PWA_OFFLINE_URL` | Página exibida sem conexão | `/pwa/offline` |
| `PWA_ICONS_PNG_PATH` | Pasta (em `public`) com PNGs 72–512px | vazio |
| `PWA_MASKABLE_ICON_PNG_PATH` | Pasta com ícones maskable 192/512 | vazio |

## Segurança

- **HTTPS**: Em produção, o app e o SW devem ser servidos exclusivamente via HTTPS (requisito dos navegadores para instalação).
- O Service Worker e o manifest não incluem dados de usuário; a versão vem de `config('pwa.cache_version')`.
- APIs `/api/pwa/installed` e `/api/pwa/ping` estão protegidas por throttle e validação de request.

## Rotas públicas

- `GET /manifest.webmanifest` — Web App Manifest
- `GET /sw.js` — Service Worker
- `GET /pwa/offline` — Página offline (cacheada pelo SW)
- `GET /api/pwa/version` — Versão atual (para force update)
- `POST /api/pwa/installed` — Registro de instalação (auth opcional)
- `GET /api/pwa/ping` — Atualizar last_seen do instalado

## Admin

No PanelAdmin, o menu PWA permite: dashboard de instalações, listagem de instalações por dispositivo e gestão de versões (publicar nova versão com ou sem force update).
