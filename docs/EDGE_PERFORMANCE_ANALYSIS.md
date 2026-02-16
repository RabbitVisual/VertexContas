# Análise de Desempenho – Edge vs Outros Navegadores

**Problema:** Travamento/gargalo perceptível no Microsoft Edge, especialmente na troca de temas.

---

## 1. Diferenças Edge vs Chromium Base

O Edge é baseado em Chromium, mas tem particularidades que podem impactar performance:

| Fator | Impacto no Edge |
|-------|-----------------|
| **Extensões/Recursos nativos** | Edge pode carregar Microsoft Shopping, Bing, Copilot, etc., consumindo memória e CPU |
| **GPU / Compositing** | Aceleração de hardware pode ter perfil diferente em algumas máquinas |
| **backdrop-filter** | Edge tem histórico de problemas de performance com `backdrop-blur` em janelas com muitos elementos |
| **transition-all** | Animar todas as propriedades em centenas de nós causa reflow/repaint em cascata – Edge pode ser mais lento |

---

## 2. Gargalos Identificados no Código (Panel User)

### 2.1 `transition-all` no container principal (CRÍTICO)
```blade
<div class="flex-1 flex flex-col overflow-hidden sm:ml-64 transition-all duration-300">
```
Quando o tema muda, **todas** as propriedades CSS do container (e filhos com herança) entram em transição. Isso força o motor a calcular e animar dezenas de propriedades.

**Solução:** Trocar para `transition-[margin,width]` ou remover transition neste container.

### 2.2 `transition-all duration-500` em cards do index
Cada card usa `transition-all duration-500` – ao trocar tema, todos animam ao mesmo tempo.

### 2.3 `backdrop-blur-md` na navbar
```blade
class="... bg-white/95 dark:bg-gray-900/95 backdrop-blur-md ..."
```
`backdrop-filter: blur()` é custoso. A cada mudança de tema, o navegador precisa recomputar o blur em toda a área afetada.

### 2.4 Theme toggle síncrono
O PanelUser usa um script simples:
```js
cb.addEventListener('change', function(){
    document.documentElement.classList.toggle('dark', isDark);
    localStorage.setItem('color-theme', ...);
});
```
Sem `requestAnimationFrame`, o repaint acontece no mesmo ciclo que o clique, podendo bloquear a UI.

### 2.5 Sidebar com `transition-all duration-200` em cada item
```php
$proNavBase = '... transition-all duration-200 group';
```
Dezenas de links no sidebar com `transition-all` – todos reagem à troca de tema.

### 2.6 MutationObserver no sidebar
O script `syncInert` usa `MutationObserver` observando `aria-hidden`. Pode disparar callbacks durante o repaint da troca de tema.

---

## 3. Recomendações (prioridade)

1. **Substituir `transition-all` por `transition-colors`** em containers grandes e em itens que reagem ao tema.
2. **Reduzir ou remover `backdrop-blur`** na navbar (ex.: `backdrop-blur-sm` ou fundo sólido).
3. **Usar `requestAnimationFrame`** no toggle de tema para agrupar o repaint.
4. **Evitar `transition-all`** em elementos com variáveis de tema (dark:).
5. **Considerar `content-visibility`** em seções longas para reduzir trabalho do motor de layout.
