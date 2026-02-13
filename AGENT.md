# Vertex Contas - Agent Context Guide

Welcome, Jules (or other AI Agent). This document is your primary source of truth for understanding the **Vertex Contas** architecture, conventions, and business logic.

## 🧠 Project Identity
- **Name:** Vertex Contas
- **Company:** Vertex Solutions LTDA
- **CEO/Architect:** Reinan Rodrigues
- **Core Business:** High-performance financial platform with modular architecture.

## 🏗️ Architecture: Modular Monolith
This project uses `nwidart/laravel-modules`. Code is NOT in `app/`. Everything is segmented into **Modules**.

### 📂 Module Structure (`/Modules`)
Each module acts as a mini-application with its own routes, controllers, views, and database migrations.

1.  **🟢 Core** (`Modules/Core`)
    *   **Role:** The kernel of the system.
    *   **Contains:** Global Layouts (`x-layout-app`), Base Controllers, Traits, Global Components (`x-icon`, `x-logo`, `x-financial-value`), Middleware (RBAC).
    *   **Key File:** `resources/views/components/layouts/app.blade.php` (Master Layout).

2.  **👤 PanelUser** (`Modules/PanelUser`)
    *   **Role:** The Dashboard for end-users (clients).
    *   **Features:** Financial summary, Ticket management (Help Center), Profile settings, Security (2FA, Password).
    *   **Routes:** Prefix `/user`, Name `paneluser.*`.

3.  **🛡️ PanelAdmin** (`Modules/PanelAdmin`)
    *   **Role:** The Command Center for administrators.
    *   **Features:** User management, System settings, Module control, Financial oversight.
    *   **Routes:** Prefix `/admin`, Name `paneladmin.*`.

4.  **🎧 PanelSuporte** (`Modules/PanelSuporte`)
    *   **Role:** Dedicated workspace for Support Agents.
    *   **Features:** Ticket queue, Knowledge Base (Wiki), Remote Inspection (Impersonation).
    *   **Routes:** Prefix `/support`, Name `panelsuporte.*`.

5.  **💳 Gateways** (`Modules/Gateways`)
    *   **Role:** Payment processing abstraction layer.
    *   **Integrations:** Stripe, Mercado Pago.

6.  **🔔 Notifications** (`Modules/Notifications`)
    *   **Role:** Centralized notification system (Database + Real-time UI).

7.  **🏠 HomePage** (`Modules/HomePage`)
    *   **Role:** Public landing page and authentication entry points.

8.  **📝 Blog** (`Modules/Blog`)
    *   **Role:** Content management for SEO.

## 🛠️ Technology Stack (Strict Adherence)

*   **Backend:** Laravel 12.x (PHP 8.2+)
*   **Frontend:**
    *   **Blade Templates:** Primary rendering engine.
    *   **Tailwind CSS 4.1:** Styling (Utility-first). configured via Vite.
    *   **Alpine.js 3.x:** JavaScript interactivity (Modals, Dropdowns, Toggles). **Do not use jQuery.**
*   **Database:** SQLite (Local/Dev), MySQL 8 (Production).
*   **Access Control:** `spatie/laravel-permission` (Roles: `admin`, `suporte`, `user`).

## 🎨 Global UI Components & Conventions

### 1. Icons (`x-icon`)
**NEVER** use raw `<svg>` or `<i>` tags for icons. Use the component:
```blade
<x-icon name="user" style="duotone" class="text-primary-500" />
```
*   **Source:** Font Awesome Pro 6 (Mapped in `config/icons.php`).
*   **Styles:** `solid`, `regular`, `light`, `duotone` (preferred).

### 2. Colors & Branding
*   **Primary:** Indigo (`indigo-600` / `#4f46e5`).
*   **Secondary:** Purple (`purple-600` / `#9333ea`).
*   **Dark Mode:** Fully supported using Tailwind's `dark:` variant and `slate-900` backgrounds.

### 3. Loading States
Use the global loading overlay for heavy actions:
```blade
<x-loading-overlay />
```

### 4. Financial Data
Always use the privacy-aware component for monetary values:
```blade
<x-core::financial-value :value="$amount" />
```
*   **Why?** Handles currency formatting and automatically blurs data during "Remote Inspection" if the user has requested privacy.

## 🧪 Testing Guidelines (`phpunit.xml`)
*   **Framework:** PHPUnit 11.
*   **DB:** SQLite In-Memory (`:memory:`) for speed.
*   **Key Path:** `tests/Feature` and `tests/Unit`.
*   **Command:** `php artisan test`

## 🤖 Workflow Integration
*   **CI/CD:** Github Actions (`jules-auto-merge.yml`, `phpunit.yml`).
*   **Environment:** use `.env.example` as the base for all CI pipelines.

---

**Note to Agent:** When modifying this system, always verify the module context (`Modules/{Module}/...`). Do not place code in the default `app/` folder unless it applies globally to the entire framework instance.
