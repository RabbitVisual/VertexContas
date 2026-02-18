# Deploy em Produção - Vertex Contas

Checklist para deixar o Vertex Contas pronto para produção. Use o template `.env.production.example` (copie para `.env` no servidor e preencha com valores reais; nunca commite o `.env` com senhas).

---

## 1. Antes do deploy

| Item | Ação |
|------|------|
| **Ambiente** | No servidor, usar `.env` com `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` com a URL final (ex.: `https://vertexcontas.com.br`). |
| **Chave** | Executar `php artisan key:generate` se `APP_KEY` estiver vazio. |
| **Banco** | Migrar: `php artisan migrate --force`. Em instalação nova, rodar `php artisan db:seed` (ou `php artisan migrate --seed`) para criar o **único usuário admin** (Reinan Rodrigues, email no seeder), gateways Stripe/Mercado Pago, blog e wiki. **Ordem:** migrations rodam primeiro (app + módulos); seeds na ordem: RolesAndPermissions → AdminUser → Gateways → Blog → Wiki. **Gateways:** ative e configure chaves em Administração > Gateways. |
| **Storage** | `php artisan storage:link` para logos e arquivos em `storage/app/public`. |
| **Frontend** | `npm ci` e `npm run build` (Vite); assets em `public/build`. |
| **Cache** | `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`. |

### Comandos em sequência (exemplo)

```bash
cp .env.production.example .env
# Editar .env com valores reais (DB, MAIL, etc.)
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
npm ci && npm run build
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

---

## 2. Cron (obrigatório)

Os e-mails, notificações e tarefas agendadas dependem do scheduler. Configure um Cron que rode **a cada minuto**:

```bash
* * * * * cd /caminho/absoluto/do/projeto && php artisan schedule:run >> /dev/null 2>&1
```

O `schedule:run` executa automaticamente:

- `queue:work database --stop-when-empty --max-time=55` (a cada minuto)
- `core:run-recurring` (transações recorrentes, diariamente às 06:00)
- `notifications:prune` (limpeza de notificações, diariamente)

Definido em `routes/console.php`.

---

## 3. Pós-deploy (validação rápida)

- Acessar a URL do site e a rota **`/up`** (health check).
- Testar **login**, **dashboard** e um fluxo crítico (ex.: criar transação, abrir chamado).
- Se usar **Stripe** ou **Mercado Pago**: enviar um evento de teste pelo painel do provedor e confirmar que o webhook responde 200 e que o estado da assinatura é atualizado. Em produção, o **Webhook Secret** do Stripe deve estar configurado no Admin (Configurações > Gateways); sem ele, webhooks Stripe são rejeitados.
- Confirmar **envio de e-mail** (ex.: "Esqueci minha senha" ou e-mail de confirmação PRO) para validar fila e SMTP.

---

## 4. Variáveis opcionais

- **GEMINI_API_KEY:** `.env` ou Admin > Configurações > IA. Se não definida, Vertex Bot e consultoria degradam graciosamente (sem IA).
- **Stripe / Mercado Pago:** Chaves e webhook secret no **Admin > Gateways**: edite cada gateway, preencha API Key, Secret Key e Webhook Secret (obrigatório para Stripe em produção). A URL do webhook é exibida na tela de edição; configure-a no painel do Stripe/Mercado Pago.
- **reCAPTCHA:** Admin > Configurações > Segurança. Opcional; sem ele o login segue normalmente.
- **Pusher (Vertex Chat VIP):** Para chat em tempo real, definir `BROADCAST_CONNECTION=pusher` e variáveis `PUSHER_*` no `.env`; caso contrário, `log` é suficiente.
