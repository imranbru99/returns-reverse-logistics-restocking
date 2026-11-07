# imrandevbd packages in this project

**Author:** [Imran Ahmed](https://imrandev.bd) — me@imrandev.bd

All AI, PDF, slug, catalog, attendance, performance, and executive-dashboard capabilities route through the packages below. This document states **purpose**, **where wiring will live** (Phase 1+), and **primary facades/APIs**.

| # | Package | Composer name |
|---|---------|-----------------|
| 1 | Drutogoti | `imrandevbd/drutogoti` |
| 2 | Laravel Admin Panel | `imrandevbd/laravel-admin-panel` |
| 3 | Laravel AI Catalog | `imrandevbd/laravel-ai-catalog` |
| 4 | Laravel AI Hub | `imrandevbd/laravel-ai-hub` |
| 5 | Laravel Attendance Hub | `imrandevbd/laravel-attendance-hub` |
| 6 | Laravel Unicode PDF | `imrandevbd/laravel-unicode-pdf` |
| 7 | Laravel Universal Slug | `imrandevbd/laravel-universal-slug` |

Path-patched copies: **drutogoti**, **laravel-admin-panel**, and **laravel-attendance-hub** under `packages/` (see `docs/DECISIONS.md`).

---

## 1. Drutogoti (`imrandevbd/drutogoti`)

**Purpose:** Production-safe performance telemetry—N+1 detection, slow queries, cache hit/miss, cookie bloat, and image optimization hints—offloaded to the queue with configurable sampling.

**Wiring:**

| Area | Integration |
|------|-------------|
| Global | Auto-registered `DrutogotiServiceProvider`; watchers attach to the HTTP kernel |
| Config | `config/drutogoti.php` (publish) or env `DRUTOGOTI_*` |
| Queue | `DRUTOGOTI_STORAGE_DRIVER=queue` uses `QUEUE_CONNECTION` |
| Ops | Filament system health widgets (Phase 43) surface top suggestions |

**Key APIs:** No facade; use `CaptureContext`, suggestion models (`ImranDevBd\Drutogoti\Models\Suggestion`), and Artisan commands from the package README. Telemetry respects `DRUTOGOTI_ENABLED` and `DRUTOGOTI_SAMPLE_RATE`.

---

## 2. Laravel Admin Panel (`imrandevbd/laravel-admin-panel`)

**Purpose:** Executive dashboards, multi-panel registry, theme engine, health/diagnostic widgets, and command-palette search—alongside Filament, not replacing it.

**Wiring:**

| Area | Integration |
|------|-------------|
| Bootstrap | Package service provider; panels registered in `App\Providers\AdminPanelServiceProvider` (Phase 11 / 37) |
| Super-admin & tenant exec views | Dashboard routes and widgets complement Filament `/super-admin` and role-based KPI pages |
| Config | `config/admin-panel.php` — module toggles per feature |

**Key facade:** `ImranDevBD\LaravelAdminPanel\Facades\AdminPanel`

```php
AdminPanel::registerPanel($panel);
AdminPanel::getCurrentPanel();
AdminPanel::getThemeRegistry();
AdminPanel::renderHook('panels::body.end');
```

---

## 3. Laravel AI Catalog (`imrandevbd/laravel-ai-catalog`)

**Purpose:** Import and enrich product catalog data from URLs; vision/metadata extraction; category taxonomy helpers; market price benchmarking for resale.

**Wiring:**

| Area | Integration |
|------|-------------|
| Module 23 Product Catalog | Import-by-URL actions, approval workflow, enrichment jobs |
| AI provider | Delegates LLM/vision calls to **AI Hub** (never direct HTTP to OpenAI/etc.) |
| Config | `config/ai-catalog.php`, env `AI_CATALOG_*` |

**Key facade:** `ImranDevBd\AiCatalog\Facades\AiCatalog` (import/scrape pipeline entrypoints per package README).

**Models / config hooks:** `AI_CATALOG_PRODUCT_MODEL`, schema column map in config for tenant `products` table.

---

## 4. Laravel AI Hub (`imrandevbd/laravel-ai-hub`)

**Purpose:** Single gateway for every AI feature—chat, structured JSON, vision, embeddings—with provider failover, per-tenant keys (database settings), and analytics.

**Wiring:**

| Area | Integration |
|------|-------------|
| Modules 16, 33, 36 | Inspection assist, support draft replies, NL analytics assistant |
| Tenant settings | `AI_HUB_SETTINGS_DB=true`; tenant-level keys override env defaults |
| UI | Optional `/ai-hub` settings UI (`AI_HUB_UI`) for platform operators |

**Key facade:** `ImranDevBd\AiHub\Facades\AIHub`

```php
AIHub::prompt('Summarize inspection')->provider('openai')->send();
AIHub::messages([...])->structured($schema)->send();
AIHub::configure('ollama', apiKey: null, model: 'llama3.3');
```

**Config:** `config/ai-hub.php`, env `AI_HUB_*`.

---

## 5. Laravel Attendance Hub (`imrandevbd/laravel-attendance-hub`)

**Purpose:** Unified drivers for ZKTeco, ADMS push, Hikvision, Suprema, Dahua, virtual/fake devices—normalized punches for workforce module.

**Wiring:**

| Area | Integration |
|------|-------------|
| Module 31 Workforce & Attendance | Device registry, sync commands, webhook/ADMS routes |
| Webhooks | ADMS/iClock push + optional IoT bridge token |
| Tests | `AttendanceHub::fake()` for Pest without hardware |

**Key facade:** `ImranDevBd\AttendanceHub\Facades\AttendanceHub`

```php
AttendanceHub::device($device)->sync();
AttendanceHub::provider('zkteco')->pullLogs($since);
AttendanceHub::catalog()->resolve('SF300');
```

**Config:** `config/attendance-hub.php`, env `ATTENDANCE_*` and `ATTENDANCE_HUB_*` aliases documented in `.env.example`.

---

## 6. Laravel Unicode PDF (`imrandevbd/laravel-unicode-pdf`)

**Purpose:** Multilingual PDF generation (Bangla, Arabic, CJK, etc.) for labels, RMA slips, inspection reports, invoices, certificates, manifests.

**Wiring:**

| Area | Integration |
|------|-------------|
| Modules 12, 20, 28–30, 40 | All outbound PDFs use this package exclusively |
| Blade | `UnicodePdf::loadView('pdf.rma-label', $data)->stream()` |
| Fonts | `UNICODE_PDF_*` + bundled Noto/fallback chain |

**Key facade:** `ImranDev\UnicodePdf\Facades\UnicodePdf`

```php
UnicodePdf::view('pdf.inspection', $data)
    ->locale(app()->getLocale())
    ->paper('a4')
    ->download('inspection.pdf');
```

---

## 7. Laravel Universal Slug (`imrandevbd/laravel-universal-slug`)

**Purpose:** Unicode-safe slugs for products, categories, CMS pages, blog posts, help articles, and tenant vanity paths (`preserve_unicode` mode for Bangla).

**Wiring:**

| Area | Integration |
|------|-------------|
| Modules 23, 34, 41 | Eloquent trait / observer on slug-enabled models |
| Public routes | Locale-prefixed URLs with native-script slugs |
| Config | `config/universal-slug.php` — mode, separator, uniqueness scope |

**Key facade:** `ImranDev\UniversalSlug\Facades\UniversalSlug` (generate, unique, transliterate helpers per package API).

```php
UniversalSlug::for($product, 'name')->unique()->save();
```

---

## Cross-package rules

1. **All LLM calls** go through `AIHub`, including AI Catalog and inspection assist.
2. **All PDFs** go through `UnicodePdf`, including billing and marketplace documents.
3. **Public URLs** for localized content use `UniversalSlug` with tenant scope.
4. **Performance regressions** are investigated with Drutogoti before release hardening (Phase 14).

See also `docs/DECISIONS.md` and the root `PROGRESS.md` for phase-level delivery status.
