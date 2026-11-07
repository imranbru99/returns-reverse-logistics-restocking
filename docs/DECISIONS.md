# Architecture & product decisions

**Author:** [Imran Ahmed](https://imrandev.bd) — me@imrandev.bd

This document records non-obvious decisions made while building the Returns & Reverse Logistics Platform. Each entry is authoritative for implementation unless superseded by a later entry with a date.

---

## Stack (2026-11-08)

### Laravel 13, Filament 5.11, Livewire 4

- **Laravel** `^13.17` is the application framework (PHP 8.3+).
- **Filament** `^5.11` is the primary admin UI: tenant back-office resources, forms, tables, widgets, and tenancy integration.
- **Livewire** 4 ships with Filament 5 and powers interactive admin and portal flows without a heavy SPA.

### Admin experience: Filament + laravel-admin-panel

- **Filament** owns CRUD, permissions-aware resources, and module-gated navigation for day-to-day operations.
- **imrandevbd/laravel-admin-panel** supplements Filament with executive dashboards, health widgets, command-palette-style navigation, and platform diagnostics. It is **not** a replacement for Filament; both run side by side with clear boundaries documented in `docs/PACKAGES.md`.

### Path-patched first-party packages

The following packages live under `packages/` and are consumed via Composer path repositories so they can declare support for `illuminate/*` ^13 before upstream tags catch up:

| Package | Path |
|---------|------|
| drutogoti | `packages/drutogoti` |
| laravel-admin-panel | `packages/laravel-admin-panel` |
| laravel-attendance-hub | `packages/laravel-attendance-hub` |

Symlinked path installs keep local patches reviewable in-repo.

---

## Multi-tenancy (2026-11-08)

- **Model:** single database, shared schema.
- **Isolation:** `tenant_id` on all tenant-owned tables, enforced by `App\Models\Scopes\TenantScope` and the `BelongsToTenant` trait on models.
- **Context:** `App\Support\TenantContext` (+ `tenant()` / `tenant_id()` helpers) holds the active tenant per request.
- **Filament:** organization-based tenancy in the tenant `/app` panel; super-admin panel is separate from tenant panels.
- **Users:** may belong to multiple tenants with distinct roles per tenant.

---

## Internationalization & search (2026-11-08)

- **Locales:** English (`en`) and Bangla (`bn`) ship on day one; translation files live under `lang/en` and `lang/bn`.
- **RTL:** layout and PDF stacks must tolerate RTL scripts even if Bangla is LTR; Arabic/Hebrew-ready via unicode PDF fallbacks.
- **Scout:** `SCOUT_DRIVER=database` for zero-ops search in development and small deployments; configuration remains **Meilisearch-ready** (host/key env vars documented in `.env.example`) for horizontal scale-up without code changes.

---

## Module system (2026-11-08)

- Registry lives in `config/modules.php` (`registry` array) synced to `modules` table via `ModuleSeeder` / `ModuleManager::syncFromRegistry()`.
- Keys are kebab-case (e.g. `inspection-engine`, `disposition-engine`) and are the single source of truth for gating, presets, and marketplace.
- `BusinessPreset::defaultModules()` returns those same keys.
- Enforcement: `module:{key}` middleware, Filament `HasModuleAccess`, `@module` Blade, cached per-tenant enabled set.
- `config/features.php` drives marketing/README feature lists (synced later via `features:sync-readme`).

---

## Infrastructure (2026-11-08)

- **Docker Compose** stack: `app` (php-fpm 8.3), `nginx`, `mysql:8`, `redis`, dedicated `queue` and `scheduler` workers, `mailpit`, optional `horizon` profile.
- **Make targets** (`make install`, `up`, `migrate`, etc.) are the supported local workflow.
- **CI:** GitHub Actions runs Pint, Larastan level 6 on `app/`, and Pest with SQLite.

---

## Revision history

| Date | Summary |
|------|---------|
| 2026-11-08 | Initial Phase 1 decisions recorded |
