# Sembark Project — Local Setup & Testing

This repository contains a Laravel-based URL shortener app with role-based dashboards (SuperAdmin, Admin, Member), company support, CSV export, and hit tracking.

Follow these steps to set up and run the project locally for development and testing.

## Prerequisites

- PHP 8.1+ (8.3 recommended)
- Composer
- MySQL
- Git

## Clone

```bash
git clone https://github.com/rajesh-kh/Urlshornerproject.git sembark_project
cd sembark_project
```

## Environment

Copy the example environment and update DB credentials:

```bash
cp .env.example .env
# Edit .env and set DB, MAIL and other values
```

Minimum `.env` settings to update:

- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `APP_URL` (e.g. http://localhost:8000)

## Install PHP dependencies

```bash
composer install
```

Generate app key and link storage:

```bash
php artisan key:generate
php artisan storage:link
```

## Database: Migrate & Seed

Create the database (in MySQL) and then run migrations and seeders:

```bash
php artisan migrate --seed
```

If you only want migrations (no seed):

```bash
php artisan migrate
```

## Run the app

Start the PHP built-in server:

```bash
php artisan serve --host=127.0.0.1 --port=8000
# then open http://127.0.0.1:8000
```

If you use Laragon, Valet, Homestead, or Docker, start your environment and point your browser at the configured URL.

## Tests

Run PHPUnit tests:

```bash
php artisan test or php artisan test --filter ShortUrlTest
```

You can run a specific test file or test name via PHPUnit options.

## Seeded data Details

- Login Details

```bash
Super Admin - superadmin@example.com
Admin User - admin@example.com
Member User - member@example.com
```

- Password for every user is "password"
