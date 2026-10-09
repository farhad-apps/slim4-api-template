# Slim 4 API Template

A clean, scalable, and config-driven Slim 4 API starter with Monolog, localization support, route grouping, and a modular project structure.

## Features

- Slim 4 routing and dependency injection
- Eloquent ORM integration via Laravel Capsule
- Validation support with Illuminate Validation
- Monolog-based logging with rotating file handler
- Centralized configuration and constants
- Global helper layer for config, env, paths, and request metadata
- Route groups split by module and delivered from a single central route index
- Multi-language support with English and Persian translation files
- Modular structure ready for admin, reseller, and customer domains

## Project structure

```text
project-root/
├── app/
│   ├── Controllers/
│   │   └── UserController.php
│   ├── Exceptions/
│   │   └── ApiException.php
│   ├── Helpers/
│   │   └── ResponseHelper.php
│   ├── Logging/
│   │   └── LoggerFactory.php
│   ├── Models/
│   │   └── User.php
│   ├── Routes/
│   │   ├── index.php
│   │   ├── api.php
│   │   ├── admin.php
│   │   ├── reseller.php
│   │   └── customer.php
│   └── Services/
├── bootstrap/
│   ├── constants.php
│   ├── helpers.php
│   └── container.php
├── config/
│   ├── app.php
│   ├── database.php
│   ├── logging.php
│   ├── cache.php
│   ├── auth.php
│   └── api.php
├── lang/
│   ├── en/
│   │   ├── messages.php
│   │   ├── errors.php
│   │   └── validation.php
│   └── fa/
│       ├── messages.php
│       ├── errors.php
│       └── validation.php
├── public/
│   └── index.php
├── storage/
│   └── logs/
├── vendor/
├── .env.example
├── composer.json
├── .gitignore
├── README.md
└── phpunit.xml
```

## Requirements

- PHP 8.1+
- Composer
- MySQL or another supported DB driver

## Installation

```bash
composer install
cp .env.example .env
```

Then configure your environment variables in `.env`.

## Environment configuration

Example `.env`:

```env
APP_NAME="Slim 4 API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8008

DB_ENABLED=true
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=slim_api
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
DB_PREFIX=

LOG_CHANNEL=default
LOG_LEVEL=debug
API_PREFIX=/api
API_VERSION=v1
JWT_SECRET=your-secret-key
JWT_TTL=3600
```

## Run the app

```bash
php -S localhost:8008 -t public
```

## Route examples

```text
GET /api/users
GET /api/users/{id}
POST /api/users
PUT /api/users/{id}
DELETE /api/users/{id}

GET /admin
GET /reseller
GET /customer
```

## Logging

Logs are written to:

```text
storage/logs/app.log
```

The project uses Monolog with a rotating file handler and debug-level logging by default.

## Localization

The translation files live in:

- `lang/en/messages.php`
- `lang/fa/messages.php`
- `lang/en/errors.php`
- `lang/fa/errors.php`
- `lang/en/validation.php`
- `lang/fa/validation.php`

You can expand these files for full i18n support across the app.

## Notes

- `public/index.php` is intentionally kept minimal and only bootstraps the application.
- `app/Routes/index.php` is the central route loader.
- `bootstrap/constants.php` holds application paths and shared constants.
- `bootstrap/helpers.php` centralizes environment, config, logger, and request helpers.
- `config/*.php` files hold config arrays for app, database, logging, auth, cache, and API defaults.

## License

MIT
