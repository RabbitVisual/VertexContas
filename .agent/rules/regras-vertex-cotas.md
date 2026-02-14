---
trigger: always_on
---

# VERTEX CONTAS - SYSTEM RULES & ARCHITECTURE

You are an expert Senior Developer working on "Vertex Contas", a high-performance modular financial SaaS.
Follow these rules strictly for every request.

## 1. ARCHITECTURE: Laravel Modular Monolith (CRITICAL)
* **Core Principle:** This project uses `nwidart/laravel-modules`.
* **Location Rule:** NEVER create files in the default `app/` or `resources/` directory for features.
* **Correct Paths:**
    * Logic: `Modules/{ModuleName}/app/`
    * Views: `Modules/{ModuleName}/resources/views/`
    * Routes: `Modules/{ModuleName}/routes/`
    * Config: `Modules/{ModuleName}/config/`
* **Active Modules:**
    * `Core`: Financial engine (Accounts, Transactions, Goals, Reports), Traits, Services.
    * `PanelUser`: Client Dashboard, Subscription, Profile.
    * `PanelAdmin`: Administration, Tenant Management, Auditing.
    * `PanelSuporte`: Help Desk, Ticket System, Wiki, Blog.
    * `Gateways`: Payment Drivers (Stripe, MercadoPago), Webhooks.
    * `Notifications`: Local Polling System.
    * `HomePage`: Landing Page, Auth.

## 2. FRONTEND STACK: "Vertex Premium"
* **CSS Framework:** **Tailwind CSS v4.1** (Native).
    * *Rule:* Use modern v4 classes. Do NOT use `@apply` unless necessary.
    * *Theme:* Dark mode is supported (`dark:bg-slate-900`). Use Glassmorphism (`backdrop-blur-md`).
* **Icons:** **FontAwesome 7.1 Pro (Local)**.
    * *Rule:* NEVER use CDN links or SVG paths directly.
    * *Rule:* NEVER use Emogi in code.
    * *Usage:* Use the `<i>` tag: `<i class="fa-pro fa-solid fa-house"></i>`.
    * *Location:* Assets are in `resources/fw-pro`.
* **JS Framework:** **Alpine.js (v3)** + Blade Components.
    * *Prohibited:* Do NOT suggest React, Vue, or Livewire unless explicitly asked.

## 3. BACKEND PATTERNS
* **Controllers:** Keep them skinny. Move business logic to **Services** inside `Modules/Core/app/Services`.
* **Routing:** Always name routes with the module prefix (e.g., `paneluser.dashboard`).
* **Strict Types:** Use `declare(strict_types=1);` in all PHP files.
* **Security:** Always use Laravel Policies (`Modules/Core/app/Policies`) for authorization.

## 4. WORKFLOW & BEHAVIOR
* **Context First:** Before writing code, analyze the existing file structure in the relevant Module.
* **No Hallucinations:** Do not invent file paths. Check if the file exists in `Modules/` first.
* **Migration Rule:** When creating migrations, use `php artisan module:make-migration {Name} {ModuleName}`.
* **Visual Style:** Ensure all UI components match the "Fintech Premium" look (Clean, rounded-xl, high contrast).

## 5. SPECIFIC FILE REFERENCES
* **Master Layouts:**
    * Admin: `Modules/PanelAdmin/resources/views/components/layouts/master.blade.php`
    * User: `Modules/PanelUser/resources/views/components/layouts/master.blade.php`
* **Global CSS:** `resources/css/app.css` (Tailwind v4 entry point).