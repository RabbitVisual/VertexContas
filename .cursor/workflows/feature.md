---
description: Cria uma nova funcionalidade seguindo a arquitetura Modular do Vertex.
---

Atue como Arquiteto de Software Sênior da Vertex Solutions.
Estou solicitando uma nova funcionalidade: "{{input}}".

Siga estritamente este checklist de implementação:
1.  **Localização:** Identifique o Módulo correto em `Modules/{Nome}/`. NUNCA crie arquivos na raiz `app/` ou `resources/`.
2.  **Estrutura:**
    * Controller: `Modules/{Nome}/app/Http/Controllers`.
    * Model: `Modules/Core/app/Models` (se for compartilhado) ou dentro do Módulo.
    * View: `Modules/{Nome}/resources/views`.
    * Rota: `Modules/{Nome}/routes/web.php` (Use `Route::middleware(['auth'])->group...`).
3.  **Padrão de Código:** Use o Service Pattern para lógica complexa. Mantenha os Controllers "magros".
4.  **Frontend Inicial:** Crie a View estendendo o layout mestre (`<x-layouts.master>`) e usando componentes Blade padrão.

Me apresente o plano de arquivos que serão criados antes de escrever o código.