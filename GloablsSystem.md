# Sistema Global de Componentes e Melhores Práticas

Este documento define os padrões de uso para componentes globais e diretrizes de UX/UI para manter a consistência e qualidade do projeto Vertex Oh Pro.

## 1. Loading Overlay (Tela de Carregamento)

Nosso componente de loading overlay foi desenhado para oferecer uma experiência "premium" e educacional, utilizando animações suaves e ícones representativos.

### Componente
`resources/views/components/loading-overlay.blade.php`

### Uso no Código
Este componente utiliza **Alpine.js** e escuta eventos globais da janela.

1.  **Incluir no Layout Master:**
    Certifique-se de que o componente está incluído no final do `<body>` do seu layout principal (ex: `master.blade.php`):
    ```html
    <x-loading-overlay />
    ```

2.  **Disparar Manualmente (JavaScript):**
    ```javascript
    // Iniciar loading com mensagem padrão
    window.dispatchEvent(new CustomEvent('start-loading'));

    // Iniciar com mensagem e ícone personalizados
    window.dispatchEvent(new CustomEvent('start-loading', {
        detail: {
            message: 'Processando matrícula...',
            icon: 'graduation-cap' // Ícones disponíveis: book-open-reader, chalkboard-user, graduation-cap, school, laptop-code, pencil-paintbrush
        }
    }));

    // Parar loading
    window.dispatchEvent(new CustomEvent('stop-loading'));
    ```

3.  **Automático:**
    *   **Envio de Forms:** O overlay aparece automaticamente em eventos `submit` de formulários, exceto se o formulário tiver o atributo `data-no-loading`.
    *   **Navegação:** Aparece no evento `beforeunload`.

### Melhores Práticas (UX/UI)

Loading overlays devem ser usados com moderação para não interromper o fluxo do usuário desnecessariamente.

*   **Evite o "Bloqueio Total"**: Não utilize overlays que bloqueiem a tela inteira para ações rápidas (< 3s) ou carregamentos em segundo plano. Prefira indicadores localizados (spinners pequenos) na própria área de conteúdo ou botão.
*   **Tempo Ideal**: Use o overlay global apenas quando o carregamento demorar mais de **3 segundos**. Ações instantâneas não precisam de feedback visual tão intrusivo.
*   **Use Skeleton Screens**: Para carregamento inicial de páginas, listas ou feeds, prefira *skeleton screens* (esqueletos do layout) em vez deste overlay. Isso reduz a percepção de espera.
*   **Animações de Marca**: O nosso overlay já utiliza cores da marca (Indigo/Purple) e ícones educacionais para reforçar o branding durante a espera.
*   **Feedback de Progresso**: Se o carregamento for muito longo (ex: upload de vídeo, relatórios complexos), prefira uma barra de progresso real com porcentagem.
*   **Evite Telas Brancas**: O overlay serve para preencher o vazio. Ele confirma ao usuário que o sistema está trabalhando.
*   **Mantenha o Contexto**: O overlay possui fundo fosco (backdrop-blur) translúcido para que o usuário ainda perceba que está na aplicação e não perca o contexto visual.

### Exemplos de Situações de Uso
*   **Correto:** Processamento de Login/Logout, Troca de Módulos, Salvamento de Configurações Complexas, Geração de Relatórios PDF.
*   **Incorreto:** Carregar mais itens em uma lista (use skeleton ou spinner no rodapé), Salvar um campo simples via AJAX (`auto-save`), Navegação entre abas rápidas.

## 2. Ícones (Font Awesome Pro)

O projeto Vertex Oh Pro utiliza **Font Awesome Pro 7.1.0** configurado localmente para garantir consistência e performance.

### Componente
`resources/views/components/icon.blade.php`

### Uso no Código
Sempre utilize o componente `<x-icon />` para renderizar ícones. Evite usar tags `<i>` diretamente.

1.  **Exemplo Básico (Estilo Duotone - Padrão):**
    ```html
    <x-icon name="user" />
    ```

2.  **Estilo Específico (Solid, Regular, Light, Brands):**
    ```html
    <x-icon name="check" style="solid" />
    <x-icon name="github" style="brands" />
    ```

3.  **Tamanhos:**
    ```html
    <x-icon name="bell" size="lg" />
    <x-icon name="cog" size="2xl" />
    ```

4.  **Classes Adicionais (Animações, Cores):**
    ```html
    <x-icon name="spinner" class="fa-spin text-primary" />
    ```

### Estilos Disponíveis
Nossa configuração suporta os seguintes estilos:

1.  **Duotone (`style="duotone"`)** - **PADRÃO**: Ícones com dois tons, oferecendo maior profundidade e visual moderno. Use para ações principais e navegação.
2.  **Solid (`style="solid"`)**: Ícones preenchidos. Use para estados ativos, botões primários ou quando a legibilidade for crítica em tamanhos pequenos.
3.  **Regular (`style="regular"`)**: Ícones delineados com espessura padrão. Use para estados inativos ou secundários.
4.  **Light (`style="light"`)**: Ícones delineados finos. Use para interfaces minimalistas ou quando houver muitos ícones juntos.
5.  **Brands (`style="brands"`)**: Logos de marcas e serviços (ex: Google, Facebook, PayPal).

### Melhores Práticas (UX/UI)

*   **Consistência Visual**: Mantenha o estilo **Duotone** como padrão para a maior parte da interface, especialmente na sidebar e cards principais. Misturar estilos (ex: Solid com Light) pode criar ruído visual.
*   **Significado Semântico**:
    *   Use `Solid` para indicar seleção ou ênfase (ex: estrela preenchida para favorito).
    *   Use `Regular` ou `Light` para itens não selecionados.
*   **Acessibilidade**: Ícones puramente decorativos já possuem `aria-hidden="true"`. Se um ícone for interativo (botão) e não tiver texto visível, adicione um `aria-label` no elemento pai.
*   **Tamanho e Espaçamento**: Evite ícones muito pequenos (< 16px) em áreas de toque. Use as classes de tamanho (`size="lg"`, `size="xl"`) para ajustar a hierarquia.
*   **Performance**: Otimizamos o Font Awesome para carregar apenas os arquivos necessários. Não importe estilos ou famílias de fontes que não estão sendo usados.

## 3. Tipografia Global (Inter & Poppins)

Para garantir legibilidade em textos longos e destaque em títulos, utilizamos uma combinação de **duas famílias tipográficas**, hospedadas localmente para máxima performance.

### Fontes Utilizadas
1.  **Inter** (`font-sans`): Fonte principal para interface (UI), textos corridos, botões e formulários. Excelente legibilidade em telas.
2.  **Poppins** (`font-display`): Fonte para títulos e destaques. Moderna e geométrica.

### Configuração Técnica
As fontes estão configuradas em `resources/css/font.css` e importadas no `app.css`.

#### Otimizações Implementadas
*   **Formato WOFF2**: Utilizamos apenas o formato `.woff2`, que oferece a melhor compressão disponível (30-50% menor que woff). Arquivos legados (.ttf, .eot, .svg) foram removidos.
*   **Self-Hosted**: Fontes hospedadas localmente (`resources/fonts/`) eliminam dependências de terceiros (Google Fonts), melhorando a privacidade e evitando conexões DNS extras.
*   **Font Display Swap**: A diretiva `font-display: swap` garante que o texto seja exibido imediatamente com uma fonte do sistema (fallback) até que a Inter/Poppins carregue, evitando o "texto invisível" (FOIT).
*   **Pesos Selecionados**: Apenas os pesos essenciais foram mantidos para reduzir o payload:
    *   **Inter**: 400 (Regular), 500 (Medium), 600 (SemiBold), 700 (Bold).
    *   **Poppins**: 400 (Regular), 700 (Bold).

### Uso no Código (Tailwind CSS)

*   **Texto Padrão (Inter)**:
    A classe `font-sans` é o padrão do Tailwind, então não é necessário adicioná-la explicitamente na maioria dos casos.
    ```html
    <p class="text-gray-600">Este texto usa a fonte Inter automaticamente.</p>
    ```

*   **Títulos (Poppins)**:
    Nossa configuração global (`app.css`) já aplica `font-family: var(--font-display)` (Poppins) para todas as tags de título (`h1` até `h6`).
    ```html
    <h1>Este título usa Poppins automaticamente</h1>
    <h2 class="text-xl font-bold">Subtítulo em Poppins</h2>
    ```

*   **Manual**:
    Se precisar forçar a fonte de título em um elemento que não é `h1-h6`:
    ```html
    <span class="font-display font-bold">Destaque em Poppins</span>
    ```

### Melhores Práticas de Tipografia
*   **Hierarquia**: Use pesos e tamanhos para criar hierarquia, não mude a fonte.
    *   Títulos: `font-bold` ou `font-semibold`.
    *   Texto UI: `font-medium` (500) é ótimo para labels e botões.
    *   Texto corrido: `font-normal` (400).
*   **Evite Importar Pesos Extras**: Se o design pedir um peso "Extra Light" (200) ou "Black" (900), avalie se é realmente necessário, pois exigirá carregar mais arquivos de fonte (aprox. 15-25KB cada).
*   **Preload**: Para melhorar a performance crítica, o navegador pode pré-carregar as fontes principais. (Configurado automaticamente pelo Vite na build de produção).

## 4. Dark Mode Global (Tailwind CSS v4.1)

O sistema utiliza a abordagem "CSS-first" nativa do Tailwind v4, integrada com Alpine.js para alternância de estado e persistência.

### Arquitetura
*   **CSS (`resources/css/app.css`)**: Define as variáveis de cor e o comportamento do seletor `@custom-variant dark`.
*   **Layout (`components/layouts/app.blade.php`)**: Contém o script *anti-flicker* no `<head>` e o estado global `x-data="{ darkMode: ... }"` no `<html>`.
*   **JavaScript (`resources/js/theme.js`)**: Helpers auxiliares e listeners para preferências do sistema.

### Como Usar

#### No HTML (Blade)
Utilize as variantes `dark:` para estilizar elementos em modo escuro.
```html
<div class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white p-4 rounded shadow">
    <h3 class="text-lg font-bold">Título do Card</h3>
    <p class="text-gray-600 dark:text-gray-400">Conteúdo adaptativo.</p>
</div>
```

#### Botão de Alternância (Toggle)
Para criar um botão que alterna o tema, basta manipular a variável `darkMode` do Alpine.js:

```html
<button @click="darkMode = !darkMode" class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800">
    <!-- Ícone Sol (aparece no Dark) -->
    <x-icon name="sun-bright" class="hidden dark:block text-yellow-400" />
    <!-- Ícone Lua (aparece no Light) -->
    <x-icon name="moon" class="block dark:hidden text-slate-600" />
</button>
```

### Script Anti-Flicker
Para evitar que a tela pisque em branco antes de carregar o tema escuro, incluímos este script crítico no `<head>` do layout principal:
```javascript
if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
} else {
    document.documentElement.classList.remove('dark');
}
```

### Variáveis CSS Globais (Theming)
As cores base (`--color-background`, `--color-surface`, etc.) mudam automaticamente conforme o tema definida em `app.css`. Use-as para consistência:

```html

## 5. Máscaras de Input (Padrão Brasil)

Implementação robusta de máscaras para formatos brasileiros (CPF, CNPJ, Telefone, Moeda) utilizando **IMask** integrado via diretiva `x-mask` do Alpine.js.

### Arquitetura
*   **Definição (`resources/js/masks.js`)**: Centraliza as configurações das máscaras (Reinan Rodrigues Pack).
*   **Integração**: Diretiva customizada `x-mask` registra automaticamente o listener de input para atualizar o `x-model`.

### Como Usar

#### Exemplo Básico (Blade + Alpine)
Basta adicionar a diretiva `x-mask` com o nome da máscara desejada. O sistema cuida da formatação.

```html
<!-- CPF -->
<input type="text" x-mask="'cpf'" x-model="form.cpf" placeholder="000.000.000-00" class="input-primary">

<!-- Telefone (Fixo e Celular com 9 dígitos automático) -->
<input type="text" x-mask="'phone'" x-model="contact.phone" placeholder="(00) 00000-0000">

<!-- Moeda (R$) -->
<input type="text" x-mask="'money'" x-model="product.price" placeholder="R$ 0,00">

<!-- Data (DD/MM/AAAA) -->
<input type="text" x-mask="'date'" x-model="user.birthdate">
```

### Máscaras Disponíveis
| Chave       | Formato                           | Exemplo            |
| :---------- | :-------------------------------- | :----------------- |
| `'cpf'`     | 000.000.000-00                    | 123.456.789-00     |
| `'cnpj'`    | 00.000.000/0000-00                | 12.345.678/0001-90 |
| `'phone'`   | (00) 0000-0000 ou (00) 00000-0000 | (11) 98888-7777    |
| `'cep'`     | 00000-000                         | 01001-000          |
| `'money'`   | R$ 0.000,00                       | R$ 1.500,50        |
| `'percent'` | 00,00%                            | 15,50%             |
| `'date'`    | DD/MM/AAAA                        | 25/12/2025         |

### Melhores Práticas (Backend)
Lembre-se que as máscaras enviam os caracteres especiais (pontos, traços). Sempre **limpe os dados** no backend antes de salvar no banco ou validar.

**Exemplo no Laravel (Request ou Controller):**
```php
// Remover tudo que não for dígito
$cpfClean = preg_replace('/\D/', '', $request->cpf);
// Salvar: 12345678900
```
Isso garante integridade dos dados e facilita buscas futuras.
