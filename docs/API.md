# HTTP API (`/api/v1`)

Base URL: `{APP_URL}/api/v1`

Authentication: [Laravel Sanctum](https://laravel.com/docs/sanctum) personal access tokens on the `web` guard. Include `Authorization: Bearer {token}`.

Tenant resolution: `api.tenant` middleware binds the token user's active tenant and applies `TenantContext`.

Rate limiting: `api` limiter (default 60/min per user or IP).

## Health

| Method | Path | Auth | Description |
|--------|------|------|-------------|
| GET | `/health` | No | Liveness probe |

## RMAs (`module:rma-management`)

| Method | Path | Description |
|--------|------|-------------|
| GET | `/rmas` | Paginated list |
| GET | `/rmas/{id}` | Show |
| POST | `/rmas` | Create |
| PATCH | `/rmas/{id}` | Update |
| DELETE | `/rmas/{id}` | Soft delete |

OpenAPI: `openapi/openapi.yaml` and marketing page `/docs/api`.

## Webhooks (inbound)

| Method | Path | Description |
|--------|------|-------------|
| POST | `/webhooks/stripe` | Stripe sandbox webhook stub (`StripeWebhookController`) |

## Outbound webhooks

Tenants configure `WebhookEndpoint` records in Filament (`api-webhooks` module). Events such as `rma.created` are delivered with HMAC header `X-Returns-Signature`.
