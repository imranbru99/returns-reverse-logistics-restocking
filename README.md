<p align="center">
  <img src="docs/screenshots/dashboard.svg" alt="Returns &amp; Reverse Logistics dashboard preview" width="100%"/>
</p>

<h1 align="center">Returns & Reverse Logistics</h1>

<p align="center"><strong>Inspection, grading, restocking and refund workflows</strong></p>

<p align="center">
  <a href="https://github.com/imranbru99/returns-reverse-logistics-restocking"><img alt="GitHub" src="https://img.shields.io/badge/GitHub-imranbru99%2Freturns-reverse-logistics-restocking-0F172A?style=for-the-badge&logo=github"/></a>
  <img alt="Laravel" src="https://img.shields.io/badge/Laravel-PHP-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/>
  <img alt="Author" src="https://img.shields.io/badge/Author-Imran%20Ahmed-14B8A6?style=for-the-badge"/>
</p>

---

## Overview

**Returns & Reverse Logistics** is a complete business application focused on real operational workflows. This README lists the full feature surface so GitHub visitors and search engines can understand every capability of the product.

## Product preview

| Dashboard | Operations |
| :---: | :---: |
| ![Dashboard](docs/screenshots/dashboard.svg) | ![Operations](docs/screenshots/operations.svg) |

## Complete feature list

### Product modules & capabilities

- MASTER PROMPT
- Docker Compose
- Every AI feature
- Warehouse staff attendance via any attendance machine
- Super Admin
- Domain actions/services pattern
- Tenant Admin / Operations Manager
- Warehouse Manager
- Finance / Accountant
- Merchant / Brand Client
- Supplier / Vendor
- Customer
- Buyer
- Tenant & Organization
- Home
- Features overview plus one dedicated page per module
- Solutions by industry
- Solutions by role
- How it works
- Pricing and plan comparison
- ROI calculator and Sustainability
- Resale marketplace storefront
- Demo login page listing each demo role with one-click login
- Generate demo images/assets locally
- A guided product tour
- N+1 prevention
- CI workflow

### Admin / Filament resources

- Announcement
- Blog Post
- Carrier Account
- Cms Page
- Customer
- Fraud Signal
- Inspection Checklist
- Inspection
- Integration Connection
- Knowledge Article
- Marketplace Listing
- Order
- Plan
- Product
- Putaway Task
- Receiving Record
- Refund
- Refurbishment Work Order
- Rma
- Shipment
- Store Credit Account
- Support Ticket
- Tenant
- Vendor Claim
- Work Task

### Domain models

- Analytics Event
- Announcement
- Auction
- Auction Bid
- Blog Post
- Carrier Account
- Category
- Cms Page
- Contact Submission
- Customer
- Destruction Certificate
- Disposition
- Exchange Order
- Fraud Signal
- Gift Card
- Inspection
- Inspection Checklist
- Integration Connection
- Integration Sync Log
- Inventory Item
- Invoice
- Knowledge Article
- Liquidation Lot
- Location
- Marketplace Listing
- Marketplace Order
- Module Definition
- Number Sequence
- Order
- Order Item
- Plan
- Product
- Putaway Task
- Receiving Record
- Recycling Partner
- Refund
- Refurbishment Work Order
- Report Definition
- Return Cost Ledger
- Return Policy
- Rma
- Rma Item
- Rma Status History
- Shift
- Shipment
- Staff Profile
- Store Credit Account
- Store Credit Ledger Entry
- Subscription
- Support Ticket

### HTTP / API controllers

- Contact APIs & screens
- Demo Login APIs & screens
- Health APIs & screens
- Locale APIs & screens
- Marketing APIs & screens
- Print Label APIs & screens
- Resale Storefront APIs & screens
- Rma APIs & screens
- Sitemap APIs & screens
- Stripe Webhook APIs & screens

### Detailed feature requirements delivered

- MASTER PROMPT — Returns & Reverse Logistics Platform (Inspection & Restocking)
- NON-NEGOTIABLE RULES
- Security first: policies on every model, tenant isolation on every query, validation on every input, rate limiting, CSRF, signed URLs, audit logs, encrypted secrets.
- Auth: Laravel Sanctum (API/SPA), Filament auth, optional 2FA (TOTP), social login (Google, Microsoft) behind a module toggle.
- Frontend for public site and customer portal: Blade + Livewire + Alpine + Tailwind (fast, SEO-friendly, no heavy SPA). Dark/light mode, fully responsive, PWA-installable.
- Testing: Pest, Livewire testing, Filament testing helpers. Static analysis: Larastan level 6+, Pint for style.
- Scraping/importing online product data: product import by URL, market price benchmarking for resale pricing, product enrichment.
- Every AI feature (any provider). All AI calls go through it, with provider/model/key configurable per tenant.
- Warehouse staff attendance via any attendance machine (ZKTeco and others), feeding shifts, productivity and payroll reports.
- ARCHITECTURE
- A ModuleManager service and Module facade: Module::enabled('inspection'), Module::for($tenant)->toggle(...).
- Dependency resolution: enabling a module auto-enables its required modules (with confirmation); disabling a module warns about dependents.
- USER TYPES (multi-type users, per-tenant roles, all seeded)
- Merchant / Brand Client (for 3PL tenants) — sees only their own returns, inventory, reports, invoices.
- Supplier / Vendor — RTV claims portal (accept/dispute claims, upload evidence).
- Customer (end consumer) — returns portal: start return, track, print label, exchange, store credit.
- MODULES (build ALL; each independently switchable)
- PUBLIC WEBSITE (many public pages, SEO-ready, fast, multilingual)
- Solutions by role (Warehouse, CX, Finance, Operations, Executives)
- How it works (return → receive → inspect → disposition → restock/resell)
- Requirements: schema.org markup, OpenGraph, sitemap.xml, robots.txt, hreflang, Lighthouse 90+, accessibility (WCAG AA), cookie consent, image optimization, speed via drutogoti.
- FILAMENT PANELS
- DEMO MODE & SEED DATA
- Demo safeguards: outbound emails/SMS/payments/carriers are sandboxed and logged, destructive settings are locked, impersonation banner shown.
- Browser/smoke test: demo login for each role, create RMA → receive → inspect → disposition → restock → refund, end to end.
- DELIVERABLES
- EXECUTION PLAN (follow in order, commit after each)
- Foundation: install Laravel + Filament, Docker, config, read all seven imrandevbd/* package READMEs and wire them in, tenancy, auth, roles/permissions, i18n, base traits.
- Module system: registry, toggles, dependency resolver, gating at every enforcement point, Module Marketplace page, presets, onboarding wizard, config/features.php.
- Core modules: tenant, users, settings, audit, notifications, documents/PDF studio.
- Hardening: performance pass with drutogoti, security review, accessibility, full test run, docs and README generation.

### Platform capabilities

- Multi-role ready structure for operators, managers and admins
- Database migrations and seeders for demo-ready local setup
- Laravel architecture with services/controllers/models separation
- Responsive UI foundation (Blade / Filament where enabled)
- Environment-based config for local and production databases
- GitHub-ready documentation with visual product previews

## Tech stack

- PHP 8.2+ / Laravel
- Filament admin resources (where enabled)
- Vite asset pipeline
- SQLite/MySQL-ready database layer
- Migrations, seeders, and test-ready structure

## Quick start

```bash
git clone https://github.com/imranbru99/returns-reverse-logistics-restocking.git
cd returns-reverse-logistics-restocking
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

## Repository

- GitHub: https://github.com/imranbru99/returns-reverse-logistics-restocking
- Developer: **Imran Ahmed** · https://imrandev.bd

---

## 🤝 Let's Connect & Collaborate

I am open to **Senior Remote Full-Stack Roles**, AI Platform Architecture Contracts, and Enterprise Technical Advisory.

**Timezone:** UTC+6 (Dhaka / Rangpur, Bangladesh) — flexible overlap with US, UK & EU hours.  
**Delivery:** Async-ready · Slack · Discord · Jira · GitHub · production-first accountability.

| Channel | Handle | Link |
| :--- | :--- | :--- |
| Portfolio | imrandev.bd | https://imrandev.bd |
| Packagist | imrandevbd | https://packagist.org/packages/imrandevbd/ |
| LinkedIn | imranbru99 | https://linkedin.com/in/imranbru99 |
| GitHub | imranbru99 | https://github.com/imranbru99 |
| WhatsApp | +880 1576-918420 | https://wa.me/8801576918420 |
| Email | me@imrandev.bd | mailto:me@imrandev.bd |
| X | @imrandev_bd | https://x.com/imrandev_bd |
| YouTube | @ImranDevBD | https://youtube.com/@ImranDevBD |
