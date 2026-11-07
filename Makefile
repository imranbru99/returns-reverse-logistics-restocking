.PHONY: install up down build migrate seed demo-reset test pint stan shell logs fresh

COMPOSE := docker compose
APP_SERVICE := app
EXEC := $(COMPOSE) exec -T $(APP_SERVICE)

install: build up
	@if [ ! -f .env ]; then cp .env.example .env; fi
	$(EXEC) composer install --no-interaction --prefer-dist --optimize-autoloader
	$(EXEC) php artisan key:generate --ansi
	$(EXEC) php artisan storage:link --ansi || true
	$(MAKE) migrate
	@echo "Install complete. Application: http://localhost:$${APP_PORT:-8080}"

up:
	$(COMPOSE) up -d

down:
	$(COMPOSE) down

build:
	$(COMPOSE) build --pull

migrate:
	$(EXEC) php artisan migrate --force --ansi

seed:
	$(EXEC) php artisan db:seed --force --ansi

demo-reset:
	$(EXEC) php artisan demo:reset --force --ansi

test:
	$(EXEC) php artisan test --ansi

pint:
	$(EXEC) ./vendor/bin/pint

stan:
	$(EXEC) ./vendor/bin/phpstan analyse --memory-limit=1G

shell:
	$(COMPOSE) exec $(APP_SERVICE) bash

logs:
	$(COMPOSE) logs -f --tail=200

fresh:
	$(EXEC) php artisan migrate:fresh --seed --force --ansi
