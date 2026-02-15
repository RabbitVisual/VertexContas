# Auditoria de Produção - Vertex Contas

**Data:** 15/02/2026  
**Objetivo:** Verificar alinhamento do sistema para deploy em hospedagem compartilhada.

---

## 1. Correções Aplicadas

### 1.1 CRÍTICO: Botões de Login Demo (Corrigido)
- **Problema:** Botões "Acesso Rápido (Demo)" com credenciais fixas (`admin@vertexcontas.com` / `password`) visíveis em **produção**.
- **Solução:** Bloqueio com `@if(app()->environment('local'))` em `Modules/HomePage/resources/views/auth/login.blade.php`.
- **Resultado:** Botões exibidos apenas em ambiente local.

### 1.2 Force HTTPS (Implementado)
- **Arquivo:** `app/Providers/AppServiceProvider.php`
- **Alteração:** `URL::forceScheme('https')` quando `APP_ENV=production`.
- **Benefício:** URLs geradas em HTTPS; sessões/cookies seguros.

### 1.3 Trust Proxies (Implementado)
- **Arquivo:** `bootstrap/app.php`
- **Alteração:** `$middleware->trustProxies(at: '*')` em produção.
- **Benefício:** Funcionamento correto atrás de Cloudflare, CDN ou proxy da hospedagem.

### 1.4 Template .env.production (Ajustado)
- **Problema:** `.env.production` estava no `.gitignore`, impedindo o uso como template de deploy.
- **Solução:** Remoção de `.env.production` do `.gitignore`.
- **Uso:** Copiar para `.env` no servidor e preencher valores reais.

---

## 2. Verificações OK

| Item | Status |
|------|--------|
| **APP_DEBUG** | Usa `env()`; defaults corretos em `.env.production` |
| **dd()/dump()** | Nenhum uso em `app/` ou `Modules/` |
| **Rota /test-error** | Apenas em `app()->environment('local')` |
| **CSRF** | Exceção somente para `webhooks/*` |
| **Senhas** | Uso de `type="password"` e `Hash::make()` |
| **Gateways** | Chaves em banco com criptografia |
| **.htaccess** | Configurado corretamente na pasta `public` |
| **Options -Indexes** | Previne listagem de diretórios |

---

## 3. Configurações Necessárias no Servidor

### 3.1 Ambiente
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY` gerado com `php artisan key:generate`
- [ ] `APP_URL` em HTTPS

### 3.2 Banco
- [ ] MySQL configurado (host, banco, usuário, senha)
- [ ] `php artisan migrate --force`

### 3.3 Storage
- [ ] `php artisan storage:link`
- [ ] Permissões 775 em `storage/` e `bootstrap/cache/`

### 3.4 Document Root
- [ ] Apache/Nginx apontando para a pasta `/public`

---

## 4. Checklist Pós-Deploy

```bash
# No servidor (via SSH ou terminal da hospedagem)
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
php artisan migrate --force  # se houver migrações pendentes
```

---

## 5. Pontos de Atenção

### 5.1 Dependências de Desenvolvimento
- `composer install --no-dev` em produção para não instalar dependências de teste.

### 5.2 Assets
- Executar `npm run build` antes do deploy (Vite).
- Garantir que `/public/build` esteja no repositório ou seja gerado no deploy.

### 5.3 Filas
- `QUEUE_CONNECTION=sync` em shared hosting (sem Redis/workers).
- Jobs serão executados de forma síncrona.

### 5.4 E-mail
- Configurar SMTP real no `.env` e/ou em Configurações do painel.
- Evitar `MAIL_MAILER=log` em produção.

---

## 6. Resumo

O sistema está adequado para produção após as alterações desta auditoria. Os riscos críticos (login demo em produção) foram corrigidos e as configurações de HTTPS e TrustProxies foram aplicadas para hospedagem compartilhada.

**Próximos passos sugeridos:**
1. Testar em ambiente staging antes do deploy final.
2. Configurar backup automático do banco de dados.
3. Revisar logs periodicamente (`storage/logs/laravel.log`).
