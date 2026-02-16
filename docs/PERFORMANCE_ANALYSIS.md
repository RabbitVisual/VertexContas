# Relatório de Análise de Performance - Vertex Contas

**Data:** 15/02/2026  
**Fonte:** HAR do navegador (127.0.0.1) + análise do código-fonte

---

## 1. Resumo Executivo

O HAR capturou navegação em **ambiente local** (127.0.0.1:8000 + Vite [::1]:5173). Os tempos de carregamento estão **elevados** mesmo para dev, indicando gargalos que tendem a piorar em produção se não mitigados.

### Tempos de Página (HAR)

| Página | Content Load | On Load |
|--------|-------------|---------|
| Homepage (/) | 1,3s | 1,3s |
| Login (1ª visita) | 2,2s | 2,2s |
| Login (2ª visita) | **8,6s** | **8,6s** |
| Support Tickets | 6,1s | 6,5s |
| Support Wiki | 5,3s | 5,7s |
| Support Dashboard | **10,6s** | **11,2s** |

**Meta para produção:** Content Load < 2s, On Load < 3s em conexão 4G simulada.

---

## 2. Troca de Tema (Dark/Light) - Análise da Lentidão

### 2.1 Fluxo Atual

1. Usuário clica no botão → `@click="darkMode = !darkMode"`
2. Alpine.js dispara `$watch('darkMode', val => {...})`
3. `document.documentElement.classList.toggle('dark', val)`
4. Toda a árvore DOM é reavaliada contra seletores `dark:`
5. Componentes com `backdrop-blur` são recomputados (custoso para GPU)
6. Elementos com `transition-all` animam cada propriedade alterada

### 2.2 Causas Identificadas

| Causa | Impacto | Arquivos Afetados |
|-------|---------|------------------|
| **Backdrop-blur em muitos elementos** | Alto – cada blur exige recomputação da composição | sidebar, navbar, drawer, cards, modais |
| **transition-all duration-300** | Médio – anima todas as propriedades ao trocar tema | navbar, sidebar, ~100+ blade files |
| **Alpine $watch no `<html>`** | Médio – reatividade em escopo global | master.blade.php |
| **Muitos imports FontAwesome** | Médio – 17 arquivos CSS de ícones | app.css |
| **Charts (Chart.js + ApexCharts)** | Alto – gráficos podem precisar redesenhar | dashboard, relatórios |

### 2.3 Conflito Detectado

- **theme.js** é importado no `app.js` mas **não é usado** – layouts usam Alpine inline
- Script anti-FOUC + Alpine `x-init` fazem a mesma operação (toggle `dark` no `<html>`)
- **Recomendação:** Unificar em uma única estratégia para evitar duplicação e race conditions

---

## 3. Recursos Carregados (HAR)

### 3.1 JavaScript (ordem de carregamento)

```
app.js → bootstrap.js → theme.js → auth-forms.js → masks.js → cep-lookup.js
      → flowbite.js → chart.js → apexcharts.js → charts.js → alpine.js → @alpinejs/collapse
```

**Problema:** Chart.js e ApexCharts são carregados em **todas as páginas**, mesmo onde não há gráficos.

### 3.2 Fontes

- Inter (regular + 700)
- Poppins (regular + 700)
- FontAwesome: **17 arquivos** (solid, regular, light, thin, duotone, sharp, brands)

**Problema:** Uso predominante de `duotone` e `solid`; importar `light`, `thin`, `sharp-*` aumenta o CSS sem necessidade em muitas telas.

### 3.3 CSS

- `app.css` carrega Tailwind + Flowbite + todos os estilos FontAwesome
- `@source` para Blade em todo o projeto – o build precisa escanear muitos arquivos

---

## 4. Recomendações Prioritárias

### 4.1 Troca de Tema (Implementar Imediatamente)

1. **Usar `requestAnimationFrame` no toggle de tema**
   - Desacoplar do ciclo síncrono do Alpine
   - Evitar repaint/reflow em cadeia

2. **Remover `transition-all` em elementos que reagem ao tema**
   - Manter apenas `transition-colors` onde for essencial
   - Ou usar `transition-[color,background-color,border-color]` para limitar o escopo

3. **Reduzir `backdrop-blur` em componentes fixos**
   - Sidebar e navbar: considerar `backdrop-blur-md` em vez de `xl`
   - Ou trocar por `bg-opacity-95` sem blur em telas lentas

4. **Debounce no listener de `prefers-color-scheme`**
   - Evitar múltiplas mudanças em sequência

### 4.2 Bundle e Carregamento

5. **Code-splitting para Chart.js e ApexCharts**
   - Carregar só nas rotas que usam gráficos (dashboard, relatórios)
   - Usar dynamic import: `() => import('chart.js')`

6. **Reduzir imports FontAwesome**
   - Manter: `fontawesome.css`, `solid.css`, `duotone.css`, `brands.css`
   - Avaliar remoção: `light`, `thin`, `sharp-*` se não forem usados em produção

7. **Preload de fontes críticas**
   - Adicionar `<link rel="preload">` para Inter e Poppins no `<head>`

### 4.3 Backend / Rede

8. **Cache de respostas**
   - Garantir headers como `Cache-Control` e `ETag` para assets estáticos
   - Avaliar cache de páginas com dados pouco voláteis

9. **Otimizar consultas do dashboard**
   - Support dashboard (~10s) sugere N+1 ou consultas pesadas
   - Revisar SupportAgentController e eager loading

10. **Compressão**
    - Habilitar Brotli/gzip no servidor para HTML, CSS e JS

---

## 5. Otimizações Implementadas (15/02/2026)

### 5.1 Code-splitting Chart.js e ApexCharts ✅
- **app.js:** Removidos Chart.js, ApexCharts e initCashFlowChart/initSpendingChart (não usados em blade).
- **charts-chartjs.js:** Novo entry point – carregado apenas em `admin.index` (painel admin).
- **charts-apex.js:** Novo entry point – carregado em: `homepage`, `core.dashboard`, `core.reports.*`, `user.index`, `admin.payments.index`.
- **Payments:** CDN ApexCharts substituído pelo bundle local.

### 5.2 FontAwesome Reduzido ✅
- **Removidos:** light, thin, duotone-regular, duotone-light, duotone-thin, sharp-* (8 arquivos).
- **Mantidos:** fontawesome.css, solid.css, regular.css, duotone.css, brands.css.

### 5.3 N+1 Corrigidos ✅
- **SupportAgentController:** `pendingComments` passa a usar `with(['post', 'user'])`.
- **PanelAdminController:** `avgFinancialScore` limitado a 100 usuários (`User::latest()->take(100)`) em vez de `User::all()`.

---

## 6. Checklist Pré-Produção

- [x] Code-splitting para charts
- [x] Reduzir FontAwesome
- [x] Revisar N+1 (SupportAgent, PanelAdmin)
- [ ] Build de produção (`npm run build`) e testes de carga
- [ ] Lighthouse (Performance, Accessibility) em modo produção
- [ ] Testar troca de tema em dispositivo real (mobile)
- [ ] Configurar cache e compressão no servidor

---

## 7. Referências

- [Flowbite Dark Mode](https://flowbite.com/docs/customize/dark-mode/)
- [Tailwind v4 dark variant](https://tailwindcss.com/docs/dark-mode)
- [Alpine.js performance](https://alpinejs.dev/advanced/reactivity)
- [Backdrop-filter performance](https://developer.mozilla.org/en-US/docs/Web/CSS/backdrop-filter#performance_concerns)
