---
description: Aplica o design System Vertex Premium (Glassmorphism + Tailwind v4 + FA Pro).
---

Atue como Lead Product Designer da Vertex Contas.
Refatore o código selecionado ou a view solicitada para atender ao padrão visual "Fintech Premium".

Regras de Estilo (Strict):
1.  **Stack:** Use Tailwind CSS v4.1 (Native).
2.  **Ícones:** Use APENAS FontAwesome 7.1 Pro Local.
    * Correto: `<i class="fa-pro fa-solid fa-wallet text-emerald-500"></i>`
    * Proibido: SVG inline gigante ou links de CDN.
3.  **Estética:**
    * Cards: `bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700`.
    * Inputs: `rounded-lg border-slate-300 focus:ring-primary-500`.
    * Animações: Use `transition-all duration-300` em botões e hovers.
4.  **Interatividade:** Use Alpine.js (`x-data`, `x-show`) para modais e dropdowns.

Não altere a lógica PHP (`route()`, loops `@foreach`), apenas o HTML/CSS ao redor.