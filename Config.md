### 1. O "Segredo" Técnico: Headless Chrome

Empresas profissionais usam ferramentas que rodam um "navegador escondido" (Chromium) no servidor. Ele renderiza o HTML exatamente como você desenhou e o transforma em PDF antes de enviar para o usuário.

**A Recomendação de Ouro:** Use o pacote **[Spatie Browsershot]()**.

* Ele usa o Puppeteer (Node.js) para controlar o Chrome.
* É o que há de mais moderno: aceita Tailwind CSS v4, JavaScript, Gráficos (ApexCharts/Chart.js) e alinhamentos perfeitos.

### 2. O Segredo do Design: CSS de Impressão (@page)

Para o PDF não sair cortado e respeitar a folha A4, você precisa definir regras específicas de CSS que o navegador de renderização vai seguir:

```css
@media print {
    @page {
        size: A4;
        margin: 1cm; /* Margens de segurança */
    }
    
    /* Evita que um card ou linha de tabela seja cortada no meio entre duas páginas */
    .no-break {
        break-inside: avoid;
    }

    /* Força o fundo das cores (Tailwind precisa disso no PDF) */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
}

/* Container fixo para simular o A4 no desenvolvimento */
.a4-container {
    width: 210mm;
    min-height: 297mm;
    margin: auto;
}

```

### 3. Anatomia de uma Invoice Profissional (Estilo Stripe)

Para não parecer um "emaranhado de coisas", o layout deve seguir esta hierarquia:

1. **Header:** Logo à esquerda (em alta resolução ou SVG) e Status (Ex: PAGO em verde) à direita.
2. **Meta Dados:** Número do relatório, Data e Cliente em blocos de 3 colunas.
3. **Tabela de Itens:** Fundo branco, linhas alternadas (zebra) em cinza ultra claro, bordas apenas horizontais.
4. **Resumo Financeiro:** Alinhado à direita no rodapé, com o Total em negrito e fonte maior.
5. **Rodapé Legal:** Termos e identificação da Vertex Solutions em fonte pequena (8pt).

---

### 🚀 Prompt Cursor: Criar Relatório PDF de Elite (Estilo Business Statement)

Para que o Cursor implemente isso para você, use este prompt detalhado:

```markdown
@.cursorrules
@Modules/Core/app/Services/ReportService.php
@Modules/Core/resources/views/documents/

**Role:** Senior UI/UX Engineer & PDF Specialist.
**Goal:** Create a world-class professional PDF template for "Consultoria PRO" and "Extratos". The design must follow the "Fintech Elite" style (Stripe/PayPal) and be optimized for A4 printing.

---

### Phase 1: Engine Setup (Information)
1. Verifique se o sistema pode usar `Spatie/Browsershot` ou `Laravel-Snappy` para renderização via servidor. Se não for possível no ambiente atual, utilize `DomPDF` com configurações estritas de A4.

### Phase 2: Professional CSS Blueprint
1. Crie um arquivo `resources/css/pdf-pro.css` ou injete no Blade:
    * Defina `@page { size: A4; margin: 1.5cm; }`.
    * Use a fonte 'Inter' ou 'Helvetica'.
    * Implemente classes `.break-before` e `.avoid-break`.
    * Garanta que as cores de fundo (bg-slate-50, etc.) sejam forçadas na impressão.

### Phase 3: High-End Layout Structure
1. **Header:** Use a logo da Vertex via base64 para garantir o carregamento. Adicione o título do documento e a data de emissão em um grid elegante.
2. **Typography:** O tamanho da fonte principal deve ser 10pt (padrão financeiro). Títulos em 14pt.
3. **Tables:** Refatore as tabelas para terem padding generoso, bordas finas (#e2e8f0) e alinhamento decimal para valores monetários.
4. **AI Conclusion:** A seção de "Conclusão Estratégica" deve vir em um box com borda lateral azul (indigo-600) para destacar a consultoria.

### Phase 4: Implementation
1. Refatore a view `consulting-report.blade.php` para seguir este novo padrão "Business Statement".
2. Remova elementos desnecessários (botões, menus de navegação) da versão de impressão.

---

**Technical Constraints:**
* O PDF deve ser gerado via servidor, não pelo `window.print()` do usuário.
* Garanta que o layout não "quebre" em relatórios com mais de 2 páginas.
