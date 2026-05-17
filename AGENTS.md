# AGENTS.md

## Project Overview

Laravel 12 + Laravel Breeze app for managing documents and sending WhatsApp notifications to lecturers (dosen). Two-process architecture:

1. **Laravel app** (`/`): main web application, PHP 8.2+
2. **WhatsApp Service** (`/whatsapp-service`): standalone Node.js Express service wrapping `whatsapp-web.js`, runs on port 3001

## Quick Start

```bash
composer run setup        # install deps, copy .env, key:generate, migrate, npm install, build
```

Or manually:
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed --class=AdminUserSeeder
npm run build
```

Then start both processes (separate terminals):
```bash
php artisan serve         # http://localhost:8000
cd whatsapp-service && npm install && node index.js   # http://localhost:3001
```

`composer run dev` starts Laravel server + queue listener + Pail logs + Vite concurrently.

## WhatsApp Service

- Entrypoint: `whatsapp-service/index.js`
- Dependencies: `express`, `cors`, `qrcode`, `whatsapp-web.js`
- Port: 3001 (configurable via `WHATSAPP_SERVICE_URL` in Laravel `.env`, default `http://localhost:3001`)
- Endpoints:
  - `GET /status` — connection status
  - `GET /qr` — QR code image for phone pairing
  - `POST /send` — send message `{ phone, message }`
  - `POST /restart` — restart client
- Uses `LocalAuth` with session data in `.wwebjs_auth/`
- Puppeteer runs headless with `--no-sandbox`

## Testing

Uses **Pest** (not PHPUnit directly). Tests auto-refresh database:
```bash
php artisan test          # or vendor/bin/pest
```

- Feature tests: `tests/Feature/`
- Unit tests: `tests/Unit/`
- `phpunit.xml` forces `DB_CONNECTION=sqlite` and `:memory:` for tests.

## Architecture

- **Auth**: Laravel Breeze scaffolding (`routes/auth.php`), email verification routes exist but may not be enforced.
- **Roles**: `role` column on `users` table. Middleware:
  - `admin` (`EnsureUserIsAdmin`) — redirects `/` if not admin
  - `dosen` (`EnsureUserIsDosen`) — restricts lecturer-only routes
- **Admin routes** (`/admin/*`):
  - User CRUD (`/admin/users`)
  - Document CRUD (`/admin/dokumens`)
  - WhatsApp management (`/admin/whatsapp`)
  - Document submissions review (`/admin/dokumens/{id}/submissions`)
- **Dosen routes** (`/dokumens/*`):
  - Submit documents
  - View own submissions
  - Send WhatsApp reminders per submission
- **Models**: `User`, `Dokumen`, `DokumenSubmission`
- **WhatsApp integration**: `App\Services\WhatsAppService` singleton calls the Node service via HTTP. Phone numbers are normalized to `62...` (Indonesian format).

## Default Credentials

Seeded by `AdminUserSeeder`:
- Admin: `admin@example.com` / `password`
- No default dosen user is seeded; create via register or admin panel.

## Environment & Config

- `.env.example` defaults to MySQL (`DB_CONNECTION=mysql`). Change to SQLite if preferred.
- `config/services.php` has `whatsapp.url` key.
- Asset build: Vite + Tailwind CSS v3 + Alpine.js. Inputs: `resources/css/app.css`, `resources/js/app.js`.
- No CI, no pre-commit hooks, no lint/typecheck config beyond Laravel Pint (in `require-dev`).
