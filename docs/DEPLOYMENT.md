# Deployment

## Docker Compose stack

Services (`docker-compose.yml`):

| Service | Role |
|---------|------|
| `app` | PHP-FPM application container |
| `nginx` | Public HTTP (port `${APP_PORT:-8080}`) |
| `mysql` | Primary database |
| `redis` | Cache, session, queue |
| `queue` | `queue:work redis` worker |
| `scheduler` | `schedule:work` |
| `mailpit` | Local SMTP capture |
| `horizon` | Optional (`--profile horizon`) queue dashboard |

Install:

```bash
make install
```

Fresh database:

```bash
make fresh
```

## Environment

Copy `.env.example` to `.env`. Critical keys:

- `APP_URL`, `APP_KEY`
- `DB_*`, `REDIS_*`
- `QUEUE_CONNECTION=redis`
- `DEMO_MODE`, `RETURNS_*` numbering prefixes
- `STRIPE_*` for billing webhook (`STRIPE_WEBHOOK_SECRET`)
- `CONTACT_RATE_LIMIT`, `PORTAL_START_RATE_LIMIT`

## Horizon & scheduler

Production: run Horizon or multiple `queue:work` workers; ensure a single scheduler leader runs `php artisan schedule:run` each minute (Compose `scheduler` service uses `schedule:work`).

## Backups

[spatie/laravel-backup](https://github.com/spatie/laravel-backup) is included. Configure disks and notification mail in `config/backup.php`, then schedule `backup:run`.

## Health checks

- Laravel `/up`
- API `GET /api/v1/health`
