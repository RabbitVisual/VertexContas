---
description: Analisa erros considerando a estrutura modular e dependências.
---

Atue como Engenheiro de Debug Sênior em Laravel 12.
Analise o erro ou comportamento inesperado descrito: "{{input}}".

Passos de Análise:
1.  **Contexto Modular:** Verifique se o erro é de namespace (ex: esquecer de importar `Modules\Core\Models\...`).
2.  **Verificação de Dados:** O campo existe no `$fillable` do Model? A migration foi rodada?
3.  **Logs:** Sugira onde olhar os logs (`storage/logs/laravel.log`) se o erro não for óbvio.
4.  **Solução:** Forneça a correção exata do código, mantendo a tipagem estrita (`declare(strict_types=1);`).

Se for um erro visual, verifique se o `npm run build` foi executado ou se as tags `@vite` estão corretas no layout do módulo.