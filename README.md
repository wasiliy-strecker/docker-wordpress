# Docker WordPress Development Stack

[![Compose validation](https://github.com/wasiliy-strecker/docker-wordpress/actions/workflows/compose.yml/badge.svg)](https://github.com/wasiliy-strecker/docker-wordpress/actions/workflows/compose.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

A reproducible local WordPress environment with MariaDB, phpMyAdmin, and Mailpit. The stack keeps infrastructure disposable while preserving database, WordPress, and captured-email data in named volumes.

It is intentionally scoped to local plugin and theme development. Services are bound to `127.0.0.1`, the database has no host port, and email is captured instead of delivered.

## Services

```text
browser --> WordPress 7 / PHP 8.3 ----> MariaDB
                 |
                 `---- SMTP ----------> Mailpit

browser --> phpMyAdmin ----------------> MariaDB
```

| Service | Default URL | Purpose |
| --- | --- | --- |
| WordPress | http://localhost:8080 | Application and administrator UI |
| phpMyAdmin | http://localhost:8081 | Local database inspection |
| Mailpit | http://localhost:8025 | Captured email UI |
| Mailpit SMTP | `localhost:1025` | SMTP access from host-side tools |

## Quick start

Requirements: Docker Engine with the Compose v2 plugin.

```bash
cp .env.example .env
docker compose config --quiet
docker compose up -d
docker compose ps
```

Open WordPress and complete its normal installation wizard. The values in `.env.example` are development-only defaults; change them if the stack is used on a shared machine.

## Develop a plugin

The local `plugins/` directory is mounted as the WordPress plugin directory `local-development`. Put the plugin entry file directly inside `plugins/`, then activate **Local Development** from the WordPress administrator UI.

The read-only must-use plugin in `mu-plugins/local-mailpit.php` configures WordPress's PHPMailer instance to deliver to Mailpit automatically.

## Operations

```bash
# Follow application logs
docker compose logs -f wordpress

# Stop containers and keep data
docker compose down

# Explicitly delete all local stack data
docker compose down --volumes
```

The final command is destructive and is intentionally never part of an automated script.

## Design choices

- Exact application image versions avoid accidental upgrades during a portfolio review.
- MariaDB health checks gate WordPress and phpMyAdmin startup.
- Named volumes keep runtime data out of Git.
- Only loopback ports are published; MariaDB remains network-internal.
- Secrets live in ignored `.env`, while `.env.example` documents the complete contract.
- Mailpit replaces the unmaintained MailHog development dependency.

## Scope and security

This stack has no TLS, production secret manager, off-host backups, monitoring, or hardened runtime policies. Do not expose it to the internet and do not reuse its sample credentials. Production WordPress requires a separate deployment design.

## License

[MIT](LICENSE)
