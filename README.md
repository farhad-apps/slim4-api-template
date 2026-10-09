# Slim 4 API Template

A clean and scalable API structure for Slim 4, using Eloquent ORM and validation.

## Project structure

```text
project-root/
├── app/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   └── UserController.php
│   │   ├── Reseller/
│   │   │   └── UserController.php
│   │   └── Customer/
│   │       └── ProfileController.php
│   ├── Middleware/
│   │   ├── AdminMiddleware.php
│   │   ├── ResellerMiddleware.php
│   │   └── CustomerMiddleware.php
│   ├── Policies/
│   │   └── UserPolicy.php
│   ├── Services/
│   │   └── UserService.php
│   ├── Helpers/
│   │   └── Translator.php
│   ├── Models/
│   │   ├── User.php
│   │   └── Order.php
│   └── Routes/
���       ├── admin.php
│       ├── reseller.php
│       └── customer.php
├── lang/
│   ├── en/
│   │   └── messages.php
│   └── fa/
│       └── messages.php
├── bootstrap/
│   └── container.php
├── config/
│   ├── app.php
│   └── database.php
├── public/
│   └── index.php
├── composer.json
├── .env.example
├── .gitignore
└── README.md
```

## Features

- Slim 4 routing and dependency injection
- Eloquent ORM integration
- Validation with Illuminate Validation
- Multi-role architecture: admin, reseller, customer
- Policy-based authorization
- Locale-aware message system with English and Persian support
- Service layer for business logic
- Middleware-based role checks

## Installation

```bash
composer install
cp .env.example .env
```

Configure the database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=slim_api
DB_USERNAME=root
DB_PASSWORD=
```

## Locales

The application supports multiple locales using the `Accept-Language` header or the `locale` variable when calling the translator manually.

Examples:

```bash
curl -H "Accept-Language: en" http://localhost:8000/admin/users
curl -H "Accept-Language: fa" http://localhost:8000/admin/users
```

Messages are stored in:

- `lang/en/messages.php`
- `lang/fa/messages.php`

## Run

```bash
php -S localhost:8000 -t public
```

## Example routes

```text
GET /admin/users
GET /admin/users/{id}
POST /admin/users
PUT /admin/users/{id}
DELETE /admin/users/{id}

GET /reseller/users
POST /reseller/users

GET /customer/profile
PUT /customer/profile
```

## Example response

```json
{
  "status": "success",
  "message": "User list.",
  "data": []
}
```

## Notes

- Controllers are grouped by role to keep code readable.
- Services contain the business logic and shared queries.
- Middleware checks access by role.
- Policies enforce permissions for each user type.

## License

MIT
