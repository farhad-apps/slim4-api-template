# Slim 4 API Template

یک ساختار تمیز و قابل گسترش برای API با Slim 4، Illuminate ORM و Validation

## ساختار پروژه

```
project-root/
├── app/
│   ├── Controllers/       # Controller ها
│   │   ├── BaseController.php
│   │   └── UserController.php
│   ├── Models/           # Eloquent Models
│   │   └── User.php
│   ├── Requests/         # Validation classes
│   │   └── UserRequest.php
│   ├── Services/         # Business Logic
│   │   └── UserService.php
│   ├── Exceptions/       # Custom Exceptions
│   │   └── ApiException.php
│   ├── Helpers/          # Helper classes
│   │   └── ResponseHelper.php
│   └── Routes/           # Routes definition
│       └── api.php
├── config/               # Configuration files
│   ├── database.php
│   └── app.php
├── bootstrap/            # Bootstrap files
│   └── container.php
├── public/               # Public entry point
│   └── index.php
├── composer.json
├── .env.example
└── README.md
```

## نصب

### 1. نصب وابستگی‌ها

```bash
composer install
```

### 2. کپی فایل .env

```bash
cp .env.example .env
```

### 3. تنظیم دیتابیس

دیتابیس خود را در فایل `.env` تنظیم کنید:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=slim_api
DB_USERNAME=root
DB_PASSWORD=
```

### 4. ایجاد جداول

برای ایجاد جدول users، یک فایل migration بسازید یا مستقیماً در دیتابیس اجرا کنید:

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## اجرای پروژه

```bash
php -S localhost:8000 -t public
```

سپس به آدرس `http://localhost:8000` بروید.

## API Endpoints

### لیست تمام کاربران
```
GET /api/users
```

### دریافت یک کاربر
```
GET /api/users/{id}
```

### ایجاد کاربر جدید
```
POST /api/users
```

**Body:**
```json
{
    "name": "علی محمدی",
    "email": "ali@example.com",
    "password": "secure_password123"
}
```

### بروزرسانی کاربر
```
PUT /api/users/{id}
```

**Body:**
```json
{
    "name": "علی محمدی",
    "email": "ali.new@example.com"
}
```

### حذف کاربر
```
DELETE /api/users/{id}
```

## ویژگی‌ها

✅ Slim 4 Framework
✅ Illuminate Database (Eloquent ORM)
✅ Illuminate Validation
✅ PHP-DI Container
✅ Custom Exception Handling
✅ Response Helper
✅ Service Layer Pattern
✅ پیام‌های خطا به فارسی

## نمونه Response

### موفقیت‌آمیز
```json
{
    "status": "success",
    "message": "لیست کاربران",
    "data": [
        {
            "id": 1,
            "name": "علی محمدی",
            "email": "ali@example.com",
            "created_at": "2024-01-01T12:00:00.000000Z",
            "updated_at": "2024-01-01T12:00:00.000000Z"
        }
    ]
}
```

### خطا
```json
{
    "status": "error",
    "message": "خطای اعتبارسنجی",
    "errors": {
        "email": [
            "ایمیل معتبر نیست."
        ]
    }
}
```

## لیسنس

MIT
