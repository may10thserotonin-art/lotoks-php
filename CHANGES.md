# Lotoks — Project Change Log & Developer Handoff Document

> **Purpose:** This document captures the complete history of changes made to the Lotoks PHP project during a debugging and improvement session. Any developer or LLM picking up this project should read this document **before** touching any code. It explains the architecture, every bug that was fixed, and why each decision was made.

---

## 1. Project Overview

**Lotoks** is a PHP-based immigration and sponsorship services website that allows users to apply for visa sponsorships, browse opportunities, and manage their documents. It was originally a React/Node.js (TypeScript) project converted to a PHP-only stack running on **XAMPP** locally.

- **Tech Stack:** PHP 8.2, MySQL 8 (via XAMPP), vanilla CSS/JS
- **Local URL:** `http://localhost/lotoks/` *(deployed in a subdirectory, NOT at the domain root)*
- **Server:** XAMPP on Windows (`C:\xampp\htdocs\lotoks`)
- **Session Auth:** PHP sessions (replaces original JWT cookie auth)
- **No framework:** Pure PHP, no Laravel, no Symfony

### Directory Structure
```
lotoks/
├── admin/                  ← Admin portal pages (ALL created in this session)
│   ├── login.php           ← Admin-only login (NEW)
│   ├── logout.php          ← Admin logout (NEW)
│   ├── index.php           ← Admin dashboard (NEW)
│   ├── users.php           ← User management page (NEW)
│   └── applications.php    ← Applications management page (NEW)
├── includes/               ← Shared PHP includes
│   ├── auth.php            ← Session auth helpers (HEAVILY MODIFIED)
│   ├── config.php          ← Global BASE path constant (REWRITTEN)
│   ├── head.php            ← HTML <head> include
│   ├── footer.php          ← Site footer (grid bugs fixed)
│   ├── navbar.php          ← Site navbar
│   ├── sidebar.php         ← User portal sidebar (Tailwind classes removed)
│   └── scripts.php         ← Shared JS + WhatsApp button
├── db/
│   ├── connect.php         ← PDO MySQL singleton
│   └── schema.sql          ← Full MySQL schema with seed admin
├── assets/
│   ├── css/style.css       ← Main stylesheet (responsive CSS appended)
│   └── js/main.js
├── login.php               ← MODIFIED: now handles both users AND admins
├── logout.php              ← MODIFIED: uses redirect() helper
├── register.php            ← MODIFIED: admin auth guard added
├── forgot-password.php     ← MODIFIED: admin auth guard + path fixes
├── reset-password.php      ← MODIFIED: admin auth guard + path fixes
├── dashboard.php           ← User portal dashboard (uses requireUserAuth)
├── apply.php               ← Application wizard
├── documents.php           ← Document manager
├── opportunities.php       ← Job/visa listings
├── eligibility.php         ← Eligibility wizard (Apply Now link fixed)
├── services.php            ← Services page (JS redirect fixed)
├── index.php               ← Homepage
├── about.php
├── contact.php             ← Grid layout fixed
└── .env                    ← DB credentials (never commit this)
```

---

## 2. Database Schema

Tables (defined in `db/schema.sql`):

| Table | Purpose |
|-------|---------|
| `admins` | Admin accounts with `role` ENUM (`super_admin`, `admin`) |
| `users` | Regular user accounts |
| `applications` | Sponsorship/visa applications submitted by users |
| `user_documents` | Files uploaded by users |
| `listings` | Job/visa opportunity listings |
| `payments` | Payment records |

### Seeded Admin Account
The schema **does NOT seed a working admin** by default — the old hash was a Laravel placeholder. The working admin credentials were set manually via a temporary PHP script during this session.

| Field | Value |
|-------|-------|
| Email | `admin@lotoks.com` |
| Password | `admin123` |
| Role | `super_admin` |

> **⚠️ IMPORTANT:** If you reset the database using `schema.sql`, the admin password hash will be wrong. Run this command from the project root using XAMPP's PHP to reset it:
> ```
> C:\xampp\php\php.exe -r "require 'db/connect.php'; $db=getDb(); $hash=password_hash('admin123', PASSWORD_BCRYPT); $db->prepare('UPDATE admins SET password_hash=? WHERE email=?')->execute([$hash,'admin@lotoks.com']); echo 'Done';"
> ```

---

## 3. Critical Architecture Rules

### 3.1 The `BASE` Constant

**This is the most important concept in the project.** Because the site lives at `/lotoks/` (a subdirectory of the XAMPP root), every URL must be prefixed with `/lotoks`.

The `BASE` constant is defined in `includes/config.php` and equals `/lotoks` locally (empty string `""` in production at domain root).

**How it was fixed:** The original code used `$_SERVER['SCRIPT_NAME']` to guess the base path. This was **broken** — when a script inside `admin/` ran, it would detect `/lotoks/admin` instead of `/lotoks`, causing all links to generate paths like `/lotoks/admin/admin/users.php`.

**The fix** — `config.php` now computes `BASE` by comparing `DOCUMENT_ROOT` against the physical path of the `includes/` directory:
```php
$docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
$projectRoot = str_replace('\\', '/', dirname(__DIR__));
$base = substr($projectRoot, strlen($docRoot)); // always "/lotoks"
define('BASE', $base);
```

### 3.2 The `redirect()` Helper

Defined in `includes/auth.php`:
```php
function redirect(string $url): never {
    if (str_starts_with($url, '/')) {
        $url = BASE . $url;  // AUTO-prepends /lotoks
    }
    header("Location: {$url}");
    exit;
}
```

**⚠️ CRITICAL RULE:** Always pass a plain path starting with `/` to `redirect()`. **NEVER** do `redirect(BASE . '/some/path')` — this will double-prefix and generate broken URLs like `/lotoks/lotoks/some/path`.

```php
// ✅ CORRECT
redirect('/dashboard.php');
redirect('/admin/index.php');

// ❌ WRONG — will create double prefix!
redirect(BASE . '/dashboard.php');
```

### 3.3 Auth Guards

There are two separate auth systems: **user** and **admin**. They use separate PHP session keys.

**User auth functions:**
```php
is_user_logged_in()   → bool
user_login($user)     → void  (sets $_SESSION['user'] + 'user_logged_in')
user_logout()         → void
get_user()            → ?array
require_user_auth()   → void  (redirects to /login.php if not logged in)
requireUserAuth()     → void  (alias, used in portal pages)
```

**Admin auth functions:**
```php
is_admin_logged_in()  → bool
admin_login($admin)   → void  (sets $_SESSION['admin'] + 'admin_logged_in')
admin_logout()        → void
get_admin()           → ?array
require_admin_auth()  → void  (redirects to /admin/login.php if not logged in)
```

All admin pages must call `require_admin_auth()` at the top. All user portal pages must call `requireUserAuth()` or `require_user_auth()`.

---

## 4. Changes Made in This Session (Chronological)

### 4.1 Grid / Mobile Layout Fixes
**Problem:** Desktop view was broken — cards that should display 4-per-row were showing 1-per-row. The footer also had a grid layout bug.  
**Root Cause:** Previous mobile fixes applied `grid-template-columns: 1fr` globally without using media queries.  
**Fix:** Replaced hardcoded inline styles with class-based CSS. Added proper `@media` breakpoints to `includes/footer.php`, `contact.php`, and the main `style.css`.

---

### 4.2 Path / Routing Bug Fixes (Absolute `/` Paths)
**Problem:** Multiple files used hardcoded absolute paths like `href="/"` or `header('Location: /login.php')`. When the site runs at `/lotoks/` (not the domain root), these paths route to `http://localhost/` instead of `http://localhost/lotoks/`.  
**Files Fixed:**
- `includes/auth.php` — Updated `redirect()` to auto-prepend `BASE`
- `logout.php` — Changed to use `redirect('/login.php')`
- `services.php` — Fixed inline JS redirect
- `reset-password.php` — Fixed JS timeout redirect + footer links
- `forgot-password.php` — Fixed footer links
- `register.php` — Fixed "Back to Home" link
- `eligibility.php` — Fixed "Apply Now – Login Required" link and back link

---

### 4.3 Admin Portal Created (Entire `admin/` directory)
**Problem:** The `admin/` folder did not exist. Any link to `/admin/` anywhere returned a 404.  
**Files Created:**
- `admin/login.php` — Admin login form (queries `admins` table)
- `admin/logout.php` — Destroys admin session, redirects to admin login
- `admin/index.php` — Admin dashboard with 4 stat cards + recent applications table
- `admin/users.php` — Full user listing from `users` table
- `admin/applications.php` — Full applications listing from `applications` table

---

### 4.4 Admin Password Hash Fix
**Problem:** The `schema.sql` seeds the admin with a hardcoded bcrypt hash that corresponds to `password` (a Laravel placeholder), NOT `admin123`. Login would always fail.  
**Fix:** Generated a fresh bcrypt hash via XAMPP's PHP CLI and updated the `admins` table `password_hash` directly.

---

### 4.5 Unified Login Page
**Problem:** Admin had no way to log in from the main `login.php`. The `admin/` directory didn't exist yet, and the user login only checked the `users` table.  
**Fix:** Modified `login.php` to check the `admins` table first, then fall through to the `users` table:
1. If email matches an admin → call `admin_login()` → redirect to `/admin/index.php`
2. If email matches a user → call `user_login()` → redirect to `/dashboard.php`
3. If neither → show "Invalid email or password" error

---

### 4.6 Double-Prefix Bug Fix
**Problem:** After updating `redirect()` to auto-prepend `BASE`, several files were still passing `redirect(BASE . '/path')`, causing `BASE` to be prepended twice: `/lotoks/lotoks/admin/index.php`.  
**Files Fixed:** `login.php`, `admin/login.php`, `admin/logout.php`  
**Rule established:** Never pass a pre-built BASE URL into `redirect()`.

---

### 4.7 Auth Security Improvements
**Problem:** The auth-only pages (`register.php`, `forgot-password.php`, `reset-password.php`) checked if a user was already logged in, but did NOT check if an admin was logged in. An admin visiting these pages would see them instead of being redirected.  
**Fix:** Added `is_admin_logged_in()` checks at the top of each of those pages:
```php
if (is_admin_logged_in()) redirect('/admin/index.php');
if (is_user_logged_in()) redirect('/dashboard.php');
```

---

### 4.8 User Portal Sidebar UI Bug (Tailwind class removal)
**Problem:** The user portal sidebar (`includes/sidebar.php`) contained leftover Tailwind CSS classes (`lg:hidden`, `fixed`, `md:hidden`, `justify-between`, `hover:bg-white/10`, etc.) from the original React conversion. Since the project uses **custom vanilla CSS only** (no Tailwind), these classes did nothing. This caused the mobile hamburger toggle button to be permanently visible on desktop, and the mobile drawer to display incorrectly.  
**Fix:** Removed all Tailwind classes from `sidebar.php` and replaced them with semantic CSS class names:
- `lg:hidden fixed top-4 left-4 z-50 ...` → `class="mobile-menu-toggle"`
- `lg:hidden fixed inset-0 bg-black/50 z-50 hidden` → `class="mobile-menu-overlay hidden"`
- `lg:hidden fixed top-0 left-0 h-full w-60 z-50` → `class="mobile-menu-drawer sidebar"`
- `flex justify-between items-center mb-8 px-2` → `class="mobile-menu-drawer-header"`
- `md:hidden` on tab bar → `class="mobile-tab-bar"` (CSS handles visibility)

**CSS added to `assets/css/style.css`:** Media queries that show `.mobile-menu-toggle`, `.mobile-menu-drawer`, and `.mobile-tab-bar` only on screens ≤1024px, and hide the desktop `.portal-sidebar` on mobile.

---

### 4.9 Admin Dashboard Responsiveness
**Problem:** The admin portal CSS had a hardcoded `margin-left: 14rem` on `.main`, and the `.sidebar` was always visible. On mobile screens, the main content was crushed or hidden behind the sidebar.  
**Fix in `admin/index.php`:**
- Added `@media (max-width: 1024px)` that sets `sidebar { transform: translateX(-100%) }` (hides sidebar off-screen)
- Added `.sidebar.open` class to slide the sidebar back in
- Added a hamburger `<button class="admin-menu-toggle">` in the topbar that adds `.open` to the sidebar
- Added `.sidebar-overlay` (dark backdrop) that closes sidebar when clicked
- Added `.table-responsive { overflow-x: auto }` wrapper around data tables so they scroll horizontally on mobile instead of overflowing

---

### 4.10 BASE Constant Full Rewrite (`config.php`)
**Problem:** When an admin script inside the `admin/` subfolder ran, `SCRIPT_NAME` was `/lotoks/admin/index.php`, so `dirname(SCRIPT_NAME)` returned `/lotoks/admin`. `BASE` became `/lotoks/admin`, causing all sidebar links to generate double paths like `/lotoks/admin/admin/users.php`.  
**Fix:** Rewrote `config.php` to calculate `BASE` using `DOCUMENT_ROOT` vs physical `dirname(__DIR__)`:
```php
$docRoot     = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$projectRoot = str_replace('\\', '/', dirname(__DIR__)); // C:/xampp/htdocs/lotoks
$base        = substr($projectRoot, strlen($docRoot));   // /lotoks
define('BASE', rtrim($base, '/'));
```
This is depth-agnostic — it always resolves to `/lotoks` no matter where the script is.

---

## 5. Pending / Future Work

The following pages are referenced in the admin sidebar but do not exist yet:

| Page | Status | Notes |
|------|--------|-------|
| `admin/applications.php` | ✅ Created | Lists all applications |
| `admin/users.php` | ✅ Created | Lists all registered users |
| `admin/application-detail.php` | ❌ Not created | View/edit a single application, change status |
| `admin/user-detail.php` | ❌ Not created | View a single user's profile and applications |
| `privacy.php` | ❌ Not created | Referenced in footer legal links |
| `terms.php` | ❌ Not created | Referenced in footer legal links |

---

## 6. Login Credentials

| Role | Email | Password | Login URL |
|------|-------|----------|-----------|
| Super Admin | `admin@lotoks.com` | `admin123` | `http://localhost/lotoks/login.php` |
| Regular User | Register at site | (your choice) | `http://localhost/lotoks/login.php` |

> Both admin and user login through the **same** `login.php` page. The page automatically detects the account type and redirects accordingly.

---

## 7. Local Development Setup

1. Install XAMPP. Ensure Apache and MySQL services are running.
2. Place project at `C:\xampp\htdocs\lotoks\`.
3. Import `db/schema.sql` into MySQL (use phpMyAdmin or MySQL CLI).
4. Run the admin password fix command (see Section 2 above).
5. Optionally edit `.env` for DB credentials (defaults: host=localhost, db=lotoks, user=root, pass="").
6. Visit `http://localhost/lotoks/`.

---

*Last updated: 2026-06-06 | Session documented by Antigravity AI assistant*

---

### 4.12 Listings CRUD, Document Tagging, Requirements Editor, Impersonation (2026-06-06)

**Files Created:**
- `admin/listings.php` — Full CRUD listings manager with Active/Trash tabs. Trash tab visible only to super_admin. Inline status toggle, AJAX edit, soft-delete/restore/permanent-delete. Empty states for each tab.
- `admin/api/listing-actions.php` — Handles create/update/soft_delete/restore/permanent_delete/fetch with `log_activity()`. Permanent delete and restore restricted to `is_super_admin()`. Returns JSON for AJAX actions, redirects for form submissions.
- `admin/api/document-verify.php` — Tags documents with `verified` (1=✅, 0=⏳, -1=❌) and `category` (Passport, Photo, CV, Bank Statement, Degree/Certificate, Medical Report, Police Clearance, Other). AJAX-only, updates UI without reload.
- `admin/api/get-requirements.php` — Returns global requirements filtered by `sponsorship_type` (or all if omitted).
- `admin/api/save-requirements.php` — Saves per-application requirements as JSON to `applications.requirements` column.
- `admin/impersonate.php` — Super admin impersonation handler. `?user_id=X` starts, `?action=stop` restores admin session.

**Files Modified:**
- `admin/includes/header.php` — Added "Listings" nav link (between Applications and Users). Users link wrapped in `is_super_admin()` check (hidden from general admin).
- `admin/assets/js/admin.js` — `viewApplication()` now also fetches requirements in parallel (`Promise.all()`). `renderAppModal()` enhanced with:
  - Document cards: doc-grid, per-doc category dropdown, verify buttons (✅/❌/⏳), color-coded left border (green/red/yellow), instant UI updates via `updateDoc()`.
  - Requirements Checklist: auto-merged from global requirements (by sponsorship_type) + saved per-app requirements. Three-state buttons (met/pending/unmet) with `setReqStatus()` and `saveRequirements()` AJAX.
  - Added `updateDoc()`, `setReqStatus()`, `saveRequirements()` helper functions.
- `admin/assets/css/admin.css` — Added `.doc-grid`, `.doc-card`, `.doc-verified/rejected/pending`, `.doc-verify-btns`/`.verify-btn`, `.req-checklist`, `.req-item`, `.req-status-btn`, `.modal-actions`, `.filter-tabs`, `.login-wrapper`/`.login-card` with fade-in animation.
- `admin/users.php` — Added "Actions" column with Impersonate button (super_admin only). Uses `event.stopPropagation()` to avoid triggering row click.
- `includes/navbar.php` — Added impersonation notice bar at top (red banner with "🔐 Impersonating {name} · Switch Back to Admin" link). Navbar `top` offset adjusted dynamically when impersonation bar is visible.
- `forgot-password.php`, `reset-password.php` — Replaced manual redirect checks with `redirect_if_logged_in()` helper.

**Key Implementation Details:**
- `renderAppModal()` now receives `allRequirements` from `get-requirements.php` fetched in parallel with application data.
- Global requirements auto-populate from `requirements` table filtered by `sponsorship_type`. Saved per-app statuses override defaults. Custom entries preserved.
- Requirements saved via AJAX to `save-requirements.php` which writes JSON to `applications.requirements` column.
- Document verification updates UI immediately (no full page reload) by toggling CSS classes and badge text.
- Impersonation uses `auth.php` helpers: `start_impersonation()`, `stop_impersonation()`, `is_impersonating()`, `get_impersonator_id()`.
- Listing form uses the same modal for both Create and Edit (AJAX fetch populates fields).
- Activity logged for: listing_created, listing_updated, listing_deleted (soft), listing_restored, listing_permanent_delete, document_verified, requirements_updated, impersonation.

### 4.11 Upload & Application API Endpoints
**Problem:** The pply.php wizard was throwing "Upload failed" because it was trying to communicate with http://localhost:3001/api/user/documents/upload (the defunct Node.js backend).
**Fix:** 
1. Created pi/user/documents/upload.php to handle secure file uploads and insert records into user_documents.
2. Created pi/user/applications.php to process the final JSON application payload and insert into pplications.
3. Updated includes/scripts.php to change API_BASE from localhost:3001 to <?= BASE ?>/api.
4. Updated pply.php fetch logic to append .php to the endpoint routes.
Files are successfully routed to uploads/documents/ directly on the server.

---

### 4.13 Admin Security Page Bug Fix & Responsiveness (2026-06-06)

**Problem:** dmin/security.php threw a PHP notice on line 18 ("Undefined array key dmin_id") because it accessed $_SESSION['admin_id'] instead of the correct $_SESSION['admin']['id']. The page also used rigid inline grid-template-columns: 1fr 1fr and flex layouts that didn't adapt to mobile screens.

**Root Cause:**
- The auth system stores admin session data as $_SESSION['admin'] (array keys: id, 
ame, email, ole) � not a flat $_SESSION['admin_id'].
- The 2FA toggle section and account info grid used hardcoded inline styles with no responsive breakpoints.

**Fix:**
- Line 18: Changed $_SESSION['admin_id'] to (int)(['admin']['id'] ?? 0) to use correct session key and prevent undefined key notice.
- Extracted inline grid/flex styles into CSS classes (.sec-2fa-active, .sec-info-grid) with @media (max-width: 600px) rules:
  - .sec-2fa-active � 2FA toggle section stacks vertically on mobile (button wraps below text).
  - .sec-info-grid � Account info grid switches from 2-column to 1-column on small screens.


---

### 4.14 Admin CSRF Token Missing from Frontend (2026-06-06)

**Problem:** During the pre-launch audit, csrf_verify_or_fail() was added to all 7 admin API endpoints (manage-requirements.php, save-requirements.php, document-verify.php, toggle-suspend.php, listing-actions.php, queue-actions.php) and csrf_verify() to staff.php. However, the frontend JavaScript and HTML forms were never updated to send the CSRF token. This meant every admin POST request silently failed with a 419 CSRF mismatch, making all admin forms, AJAX saves, and action buttons non-functional.

**Affected Files (8 modified):**
- admin/includes/footer.php � Exposed CSRF_TOKEN in window.LOTOKS_CONFIG
- admin/requirements.php � Added _csrf to saveRequirements() POST body
- admin/listings.php � Added csrf_field() to listing form + _csrf to all 6 AJAX calls (editListing, softDeleteListing, restoreListing, permanentDelete, toggleActive fetch and update)
- admin/staff.php � Added csrf_field() to both add/edit modal form and inline delete form
- admin/assets/js/admin.js � Fixed 4 AJAX call sites (updateDoc, saveRequirements(appId), toggleSuspend, deleteApplication) + added hidden _csrf input to the application action form

**Root Cause:** The Readiness audit added server-side CSRF checks but missed the frontend counterpart � a classic 'added security middleware without updating consumers' bug.

**Fix Pattern:** All AJAX calls now read window.LOTOKS_CONFIG.CSRF_TOKEN and append &_csrf= to the POST body. All HTML form elements use <?= csrf_field() ?> to render a hidden _csrf input.

