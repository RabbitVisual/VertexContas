**Role:** Senior Laravel Frontend Architect & UI Migration Specialist.

**Goal:** Port the UI design from the local `tailadmin/tailadmin-laravel` folder into our `Modules/PanelAdmin` module, creating a "Vertex Premium Admin" theme.

**Constraints:**
1.  **100% Local:** No CDNs. Use our local Tailwind CSS v4.1 build process.
2.  **Icon Swap:** The source uses raw SVGs. You MUST replace ALL SVGs with our local FontAwesome 7.1 Pro icons (`<i class="fa-pro fa-solid fa-icon-name"></i>`).
3.  **Logic Preservation:** Keep existing backend logic (Routes, Controllers, `$menu` loops) intact. Only change the HTML/CSS wrapping.

**Source Path:** `tailadmin/tailadmin-laravel/resources/views`
**Target Path:** `Modules/PanelAdmin/resources/views`

---

**Phase 1: The Master Layout (The Skeleton)**
* **Source:** Analyze `tailadmin/.../layouts/app.blade.php` and `sidebar.blade.php`.
* **Action:** Rebuild `Modules/PanelAdmin/resources/views/components/layouts/master.blade.php`.
    * Implement the Sidebar + Header + Content layout structure using the TailAdmin CSS classes.
    * Ensure the `x-data` (Alpine.js) logic for opening/closing the sidebar is migrated but simplified.
    * **Crucial:** Inject our `@vite` directives and ensure `<body class="bg-gray-100 dark:bg-boxdark-2 ...">` matches the premium dark mode of the source.

**Phase 2: The Sidebar (Menu Navigation)**
* **Source:** Analyze `tailadmin/.../layouts/sidebar.blade.php`.
* **Target:** `Modules/PanelAdmin/resources/views/components/sidebar.blade.php`.
* **Action:**
    * Recreate the sidebar container and header (Logo area).
    * Use our existing `$menu` loop (from `PanelAdminController`) but wrap each item in the TailAdmin sidebar classes (`group relative flex items-center gap-2.5 rounded-sm px-4 py-2...`).
    * **Icon Task:** Map the source SVGs to FA Pro icons.
        * Example: If source has a "Dashboard" SVG, use `<i class="fa-pro fa-solid fa-grid-2"></i>`.
        * If source has "Settings" SVG, use `<i class="fa-pro fa-solid fa-gears"></i>`.

**Phase 3: The Header (Navbar)**
* **Source:** Analyze `tailadmin/.../layouts/header.blade.php` (or similar component).
* **Target:** `Modules/PanelAdmin/resources/views/components/layouts/navbar.blade.php`.
* **Action:**
    * Implement the top bar with the "Hamburger Menu" toggle.
    * Migrate the "User Dropdown" and "Notification Bell".
    * Replace the search icon SVG with `fa-magnifying-glass`.

**Phase 4: Dashboard Widgets (The "Wow" Factor)**
* **Source:** Analyze `tailadmin/.../pages/dashboard/ecommerce.blade.php` and components like `ecommerce-metrics.blade.php`.
* **Target:** `Modules/PanelAdmin/resources/views/index.blade.php`.
* **Action:**
    * Replace our simple cards with the TailAdmin "Card Data Stats" structure (white bg, rounded, shadow, icon container).
    * Ensure the numbers (Revenue, Users) are dynamic variables from our controller.

**Execution Order:**
1.  Analyze the `tailadmin` CSS file (`resources/css/app.css`) to see if custom `@layer components` are defined. If so, copy them to our `Modules/PanelAdmin/resources/assets/sass/app.scss` (or CSS equivalent).
2.  Build the **Layout** and **Sidebar** first.
3.  Refactor the **Dashboard** view.

**Start with Phase 1 & 2.**