# Modules

Registry source: `config/modules.php`. Tenants enable modules via `tenant_modules` and the Filament marketplace.

## Tenant & Organization (`tenant-organization`)

- **Purpose:** Organization profile, branding, and tenancy boundaries.
- **Category:** core
- **Dependencies:** none
- **Price tier:** free
- **Default enabled:** yes
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Users, Roles & Permissions (`users-roles-permissions`)

- **Purpose:** Team access control scoped per tenant.
- **Category:** core
- **Dependencies:** tenant-organization
- **Price tier:** free
- **Default enabled:** yes
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Settings & Customization (`settings-customization`)

- **Purpose:** Tenant settings, feature flags, and defaults.
- **Category:** core
- **Dependencies:** tenant-organization
- **Price tier:** free
- **Default enabled:** yes
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Audit Log & Compliance (`audit-log-compliance`)

- **Purpose:** Immutable activity history and exports.
- **Category:** core
- **Dependencies:** tenant-organization
- **Price tier:** starter
- **Default enabled:** yes
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Notifications Center (`notifications-center`)

- **Purpose:** Email, SMS, and in-app notification routing.
- **Category:** core
- **Dependencies:** tenant-organization, users-roles-permissions
- **Price tier:** starter
- **Default enabled:** yes
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Multi-language, Multi-currency & Localization (`localization`)

- **Purpose:** Locales, currencies, and regional formats.
- **Category:** core
- **Dependencies:** tenant-organization
- **Price tier:** free
- **Default enabled:** yes
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Billing & Subscriptions (SaaS) (`billing-subscriptions`)

- **Purpose:** Plans, metering, invoices, and payment collection.
- **Category:** platform
- **Dependencies:** tenant-organization
- **Price tier:** enterprise
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Product Catalog (`product-catalog`)

- **Purpose:** SKU master data, variants, and attributes.
- **Category:** inventory
- **Dependencies:** tenant-organization
- **Price tier:** starter
- **Default enabled:** yes
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Inventory Management (`inventory-management`)

- **Purpose:** Stock levels, locations, and adjustments.
- **Category:** inventory
- **Dependencies:** product-catalog
- **Price tier:** starter
- **Default enabled:** yes
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Warehouse Management (`warehouse-management`)

- **Purpose:** Zones, bins, and warehouse operations.
- **Category:** inventory
- **Dependencies:** inventory-management
- **Price tier:** growth
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Orders & Customers (`orders-customers`)

- **Purpose:** Order ingestion and customer profiles.
- **Category:** returns
- **Dependencies:** tenant-organization, product-catalog
- **Price tier:** starter
- **Default enabled:** yes
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Return Policy Engine (`return-policy-engine`)

- **Purpose:** Rules for windows, eligibility, and fees.
- **Category:** returns
- **Dependencies:** orders-customers
- **Price tier:** starter
- **Default enabled:** yes
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## RMA Management (`rma-management`)

- **Purpose:** Return merchandise authorization workflows.
- **Category:** returns
- **Dependencies:** return-policy-engine, orders-customers
- **Price tier:** starter
- **Default enabled:** yes
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Customer Returns Portal (`customer-returns-portal`)

- **Purpose:** Self-service returns for shoppers.
- **Category:** returns
- **Dependencies:** rma-management
- **Price tier:** growth
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Exchanges & Store Credit (`exchanges-store-credit`)

- **Purpose:** Exchange orders and store-credit issuance.
- **Category:** returns
- **Dependencies:** rma-management, orders-customers
- **Price tier:** growth
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Refunds & Credits (`refunds-credits`)

- **Purpose:** Refund execution and ledger tracking.
- **Category:** money
- **Dependencies:** rma-management, finance-cost-accounting
- **Price tier:** growth
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Shipping & Labels (`shipping-labels`)

- **Purpose:** Carrier rates, labels, and tracking events.
- **Category:** logistics
- **Dependencies:** rma-management
- **Price tier:** growth
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Drop-off Locations & Pickup Points (`drop-off-locations`)

- **Purpose:** Partner locations and locker handoffs.
- **Category:** logistics
- **Dependencies:** shipping-labels
- **Price tier:** growth
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Receiving & Dock Management (`receiving-dock`)

- **Purpose:** Inbound receiving sessions and ASN matching.
- **Category:** logistics
- **Dependencies:** warehouse-management, shipping-labels
- **Price tier:** growth
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Inspection Engine (`inspection-engine`)

- **Purpose:** QC checklists, grades, and evidence capture.
- **Category:** inspection
- **Dependencies:** receiving-dock
- **Price tier:** starter
- **Default enabled:** yes
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## AI Inspection Assist (`ai-inspection-assist`)

- **Purpose:** Computer-assisted defect detection and triage.
- **Category:** inspection
- **Dependencies:** inspection-engine
- **Price tier:** enterprise
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Disposition Engine (`disposition-engine`)

- **Purpose:** Resell, refurbish, scrap, and vendor routes.
- **Category:** inspection
- **Dependencies:** inspection-engine, inventory-management
- **Price tier:** growth
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Restocking & Putaway (`restocking-putaway`)

- **Purpose:** Putaway tasks and shelf replenishment.
- **Category:** inventory
- **Dependencies:** disposition-engine, warehouse-management
- **Price tier:** growth
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Refurbishment & Repair (`refurbishment-repair`)

- **Purpose:** Repair operations and parts consumption.
- **Category:** inventory
- **Dependencies:** disposition-engine, inventory-management
- **Price tier:** enterprise
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## RTV & Vendor Claims (`rtv-vendor-claims`)

- **Purpose:** Return-to-vendor shipments and supplier claims.
- **Category:** inventory
- **Dependencies:** inventory-management, receiving-dock
- **Price tier:** growth
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Fraud & Abuse Prevention (`fraud-abuse-prevention`)

- **Purpose:** Risk scoring and return abuse signals.
- **Category:** returns
- **Dependencies:** rma-management, orders-customers
- **Price tier:** enterprise
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Warranty & Claims (`warranty-claims`)

- **Purpose:** Manufacturer warranty and extended coverage.
- **Category:** returns
- **Dependencies:** rma-management, product-catalog
- **Price tier:** growth
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Finance & Cost Accounting (`finance-cost-accounting`)

- **Purpose:** Landed cost, COGS, and margin analytics.
- **Category:** money
- **Dependencies:** tenant-organization
- **Price tier:** growth
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Resale Marketplace (B-stock) (`resale-marketplace`)

- **Purpose:** List recovered inventory on secondary channels.
- **Category:** revenue
- **Dependencies:** inventory-management, product-catalog, disposition-engine
- **Price tier:** enterprise
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Liquidation & Auctions (`liquidation-auctions`)

- **Purpose:** Auction lanes for surplus and salvage lots.
- **Category:** revenue
- **Dependencies:** resale-marketplace, inventory-management
- **Price tier:** enterprise
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Recycling, Donation & Sustainability (`recycling-sustainability`)

- **Purpose:** EWaste, donation partners, and ESG reporting.
- **Category:** revenue
- **Dependencies:** disposition-engine
- **Price tier:** growth
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Workforce & Attendance (`workforce-attendance`)

- **Purpose:** Shift attendance and device integrations.
- **Category:** people
- **Dependencies:** users-roles-permissions
- **Price tier:** growth
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Tasks & Work Queues (`tasks-work-queues`)

- **Purpose:** Operational work queues and assignments.
- **Category:** people
- **Dependencies:** users-roles-permissions
- **Price tier:** starter
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Customer Support Desk (`customer-support-desk`)

- **Purpose:** Tickets, SLAs, and omnichannel inbox.
- **Category:** people
- **Dependencies:** notifications-center, knowledge-base
- **Price tier:** growth
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Knowledge Base / Help Center (`knowledge-base`)

- **Purpose:** Macros, articles, and searchable help.
- **Category:** people
- **Dependencies:** tenant-organization
- **Price tier:** starter
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Analytics & Reporting (`analytics-reporting`)

- **Purpose:** Operational KPIs and pivot reporting.
- **Category:** intelligence
- **Dependencies:** tenant-organization
- **Price tier:** starter
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## AI Insights & Forecasting (`ai-insights-forecasting`)

- **Purpose:** ML forecasts for returns and recovery.
- **Category:** intelligence
- **Dependencies:** analytics-reporting
- **Price tier:** enterprise
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Executive Dashboards (`executive-dashboards`)

- **Purpose:** Leadership scorecards and drill-downs.
- **Category:** intelligence
- **Dependencies:** analytics-reporting
- **Price tier:** enterprise
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Integrations Hub (`integrations-hub`)

- **Purpose:** Commerce, ERP, and carrier connectors.
- **Category:** platform
- **Dependencies:** tenant-organization
- **Price tier:** growth
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Public REST API & Webhooks (`api-webhooks`)

- **Purpose:** Developer APIs and outbound webhooks.
- **Category:** platform
- **Dependencies:** integrations-hub
- **Price tier:** enterprise
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Documents & PDF Studio (`documents-pdf-studio`)

- **Purpose:** Templates, merge fields, and print packs.
- **Category:** platform
- **Dependencies:** tenant-organization
- **Price tier:** starter
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Public Website CMS (`public-website-cms`)

- **Purpose:** Marketing site and content blocks.
- **Category:** platform
- **Dependencies:** tenant-organization
- **Price tier:** growth
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Backup, Export & System Health (`backup-system-health`)

- **Purpose:** Backups, data export, and health probes.
- **Category:** core
- **Dependencies:** tenant-organization
- **Price tier:** starter
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.

## Demo Mode (`demo-mode`)

- **Purpose:** Sandbox demos with reset and sample data.
- **Category:** platform
- **Dependencies:** tenant-organization
- **Price tier:** free
- **Default enabled:** no
- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.
- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.
- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.
