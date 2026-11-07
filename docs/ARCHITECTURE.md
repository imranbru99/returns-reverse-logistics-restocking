# Architecture

**Author:** [Imran Ahmed](https://imrandev.bd) — me@imrandev.bd

## Overview

Multi-tenant SaaS for returns, inspection, disposition, restocking, and revenue recovery. Single MySQL database with `tenant_id` isolation, Filament admin panels, Blade/Livewire public site and customer portal, versioned REST API.

## Layers

| Layer | Responsibility |
|-------|----------------|
| HTTP (controllers, Livewire, Filament) | Thin; authorize + validate + dispatch |
| Actions / Services | Domain operations and state transitions |
| Models | Persistence, relations, casts; no fat business logic |
| Jobs / Events | Async side effects (notifications, sync, AI) |
| Modules | Feature packages gated by `ModuleManager` |

## Tenancy

1. Resolve tenant via Filament tenant slug, custom domain, or subdomain middleware.
2. `TenantContext::set()` + Spatie `setPermissionsTeamId()`.
3. `BelongsToTenant` + `TenantScope` filter every tenant-owned query.
4. Policies assert record `tenant_id` matches active tenant (or platform admin).

## Module gating

Enforcement points: `module:{key}` middleware, Filament `HasModuleAccess`, policies, scheduler guards, `@module` Blade, API middleware, navigation builders. Disabled modules return 404/403 — not merely hidden UI.

## Panels

| Path | Audience |
|------|----------|
| `/super-admin` | Platform owner / support |
| `/app` | Tenant back-office (default) |
| `/warehouse` | Scanner-first warehouse PWA |
| `/merchant` | 3PL merchant clients |
| `/vendor` | Supplier RTV claims |
| `/portal` | End-customer returns (Livewire) |

## Packages

See `docs/PACKAGES.md` for imrandevbd integrations (Drutogoti, Admin Panel, AI Hub, AI Catalog, Attendance Hub, Unicode PDF, Universal Slug).

## State machines

RMA, inspection, shipment, and refund entities use explicit status enums with transition services that record history and fire domain events.

## Security

Policies on every model, Sanctum for API, rate limiters (`api`, `returns`), CSRF on web, signed URLs for labels/PDFs, encrypted 2FA secrets, activity log audit trail. Details in `docs/SECURITY.md`.
