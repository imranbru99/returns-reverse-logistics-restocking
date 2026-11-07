# Security

## OWASP ASVS mapping (selected)

| Control | Implementation |
|---------|------------------|
| A01 Broken access control | Tenant global scopes, `TenantContext`, Filament tenancy, Spatie roles per tenant, `EnsureModuleEnabled` middleware |
| A02 Cryptographic failures | Encrypted integration credentials, webhook secrets, HTTPS via nginx, signed portal label URLs |
| A03 Injection | Eloquent parameter binding, FormRequest / Livewire validation |
| A04 Insecure design | Module marketplace dependency checks, audit log (activity log) |
| A05 Security misconfiguration | `APP_DEBUG` off in production, demo mode isolates mail/payments |
| A06 Vulnerable components | Composer lockfile, dependabot recommended |
| A07 Authentication failures | Sanctum tokens, Filament session middleware, optional 2FA columns on users |
| A08 Software/data integrity | Git deploy tags, webhook HMAC verification |
| A09 Logging & monitoring | Activity log, DrutoGoti query logging, Horizon (optional profile) |
| A10 SSRF | Webhook dispatcher restricts to tenant-configured URLs (operational review required) |

## Upload safety

`App\Contracts\ScansUploadedFiles` is bound to `NullVirusScanner` by default; swap the implementation for ClamAV or a cloud scanner in production.

## Rate limits

| Limiter | Default | Routes |
|---------|---------|--------|
| `contact` | 5/min | `POST /contact` |
| `portal-start` | 20/min | `/portal/*` Livewire entry |
| `api` | 60/min | `/api/*` |
| `returns` | 120/min | Reserved for returns APIs |

## Lazy loading

`Model::preventLazyLoading()` is enabled outside production (`AppServiceProvider`).
