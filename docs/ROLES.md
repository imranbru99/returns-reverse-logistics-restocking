# Roles & permissions

Platform and tenant roles are defined in `App\Enums\UserType` and seeded in `RolePermissionSeeder`.

## Platform roles

| User type | Filament panel | Notes |
|-----------|----------------|-------|
| `platform_super_admin` | `/super-admin` | Full platform access, tenant plans, CMS |
| `platform_support` | `/super-admin` | Support operations (no destructive defaults) |

## Tenant roles (default permissions)

| Role key | Label | Default permissions |
|----------|-------|---------------------|
| `tenant_owner` | Tenant Owner | All seeded abilities including `modules.manage` |
| `tenant_admin` | Tenant Admin | All abilities except `modules.manage` |
| `operations_manager` | Operations Manager | Returns, inspection, warehouse ops, disposition (no deletes on master data) |
| `customer_service_agent` | Customer Service Agent | Customers, orders, RMA create/update, refunds view/create |
| `inspector` | Inspector / QC | RMA view, inspection create/update, receiving create |
| `finance` | Finance | Orders view, RMA view, refund create/update |
| `auditor` | Auditor | Read-only across customers, products, orders, RMA, inspection, refunds |

Additional user types (`warehouse_manager`, `receiving_clerk`, `restocking_operator`, `refurbishment_technician`, `merchant`, `supplier`, `carrier`, `customer`, `buyer`, `api_client`) map to Spatie role names equal to their enum value; assign permissions per tenant policy as you onboard specialized panels (`/warehouse`, `/merchant`, `/vendor`).

## Permission naming

Abilities follow `{resource}.{action}` (for example `rma.transition`, `inspection_checklist.update`). Policies live under `app/Policies` and are auto-discovered via `AppServiceProvider`.

## Team scoping

Spatie Permission uses tenant id as the team key during Filament and portal requests (`setPermissionsTeamId`).
