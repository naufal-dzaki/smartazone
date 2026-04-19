# Smartazone

A **Laravel** web application for mountain hiking operations: bookings, hiker health monitoring, location tracking, complaints and equipment management, and **SOS** alerts with realtime notifications ([Pusher Channels](https://pusher.com/channels)).

---

## Requirements

| Requirement | Notes |
|-------------|--------|
| PHP | ^8.2 |
| Composer | 2.x |
| Node.js & npm | Frontend build (Vite) |
| Database | MySQL (see `DB_*` in `.env`) |
| Common PHP extensions | `pdo_mysql`, `openssl`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo` |

---

## Setup from GitHub

### 1. Clone the repository

```bash
git clone <YOUR_REPOSITORY_URL>.git
cd smartazone
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Environment

Copy the example env file and generate an application key:

```bash
# Windows (cmd/PowerShell)
copy .env.example .env

# Linux / macOS
# cp .env.example .env

php artisan key:generate
```

Edit `.env` and set at least:

- **`APP_URL`** — Base URL of the app (e.g. `http://127.0.0.1:8000`). It should match how you open the site in the browser.
- **`APP_ENV`** — `local` for development, `production` for live servers.
- **`DB_*`** — MySQL host, database name, username, and password.
- **`BROADCAST_DRIVER`** — Use `pusher` for realtime SOS alarms; use `log` if you want to develop without Pusher.
- **`PUSHER_APP_*`** and **`VITE_PUSHER_APP_*`** — From the [Pusher Channels dashboard](https://dashboard.pusher.com/) (public key and cluster must match between server and frontend build).
- **`APP_DOMAIN`** — Optional; defaults are defined in `config/app.php`.

See `.env.example` for the full variable list and inline comments.

### 4. Database

Create an empty MySQL database, then run migrations:

```bash
php artisan migrate
```

Optional demo data (if seeders are provided):

```bash
php artisan db:seed
```

The example `.env` uses **database** drivers for session and cache — ensure migrations that create `sessions`, `cache`, and `jobs` tables (when applicable) have been run.

### 5. Frontend assets

**Development** (hot module replacement):

```bash
npm run dev
```

**Production build** (outputs to `public/build`):

```bash
npm run build
```

---

## Running the application

During development, the HTTP server, queue worker, and Vite should usually run **at the same time**.

### Option A — Single command (recommended)

The Composer script starts the Laravel server, queue worker, log tail (Pail), and Vite in parallel:

```bash
composer run dev
```

### Option B — Manual (multiple terminals)

| Terminal | Command | Purpose |
|----------|---------|---------|
| 1 | `php artisan serve` | Web server (default `http://127.0.0.1:8000`) |
| 2 | `npm run dev` | Vite for CSS/JS |
| 3 | `php artisan queue:listen` | Processes queued jobs when `QUEUE_CONNECTION=database` in `.env` |

Without `npm run dev`, you can run **`npm run build` once** and then use only `php artisan serve` (rebuild after JS/CSS changes).

### Health check

Laravel exposes a built-in route (no auth):

```http
GET /up
```

---

## User roles and authentication

- **Guest** — Landing page, public booking, authentication pages.
- **`admin`** — Mountain dashboard (`/dashboard/...`): hikers, SOS, health, location, etc. (`middleware` `role:admin`).
- **`superadmin`** — Master mountain records (`/superadmin/...`).

Web login: **`GET /login`**.

The REST routes in `routes/api.php` are **not** protected with Sanctum today — for production, add safeguards (API keys, Sanctum, rate limiting, HTTPS).

---

## REST API documentation

All routes below use the **`/api`** prefix (Laravel default).

Example base URL: `http://127.0.0.1:8000/api`.

Recommended headers:

```http
Content-Type: application/json
Accept: application/json
```

### Summary

| Method | Path | Description |
|--------|------|-------------|
| `POST` | `/api/update-log` | Submit sensor / health and location logs from an active hiker device |
| `POST` | `/api/sos-trigger` | Trigger an emergency SOS from a device |

---

### `POST /api/update-log`

Stores one row in **`mountain_hiker_logs`** for a **device** tied to an **active** hiker (`mountain_hiker_status.status = active`). Optionally updates **`battery_level`** on `mountain_devices`.

#### Request body (JSON)

| Field | Required | Type | Notes |
|-------|----------|------|--------|
| `device_id` | Yes | integer | Must exist in `mountain_devices` |
| `heart_rate` | No | number | 0–250 |
| `stress_level` | No | number | 0–100 |
| `spo2` | No | number | 0–100 |
| `lattitude` | No | number | Latitude, −90 to 90 *(field name matches current code spelling)* |
| `longitude` | No | number | Longitude, −180 to 180 |
| `timestamp` | No | datetime string | If set, used as the sample time |
| `battery_level` | No | any | If present, updates the device `battery_level` column |

#### Success response

- **HTTP 201** — `status`: `"success"`, `message`, and `data` with the saved log row.

#### Error responses

- **422** — Validation failed (`message` contains field details).
- **404** — Device has no active hiker registration.
- **500** — Server error.

#### Example (`curl`, Windows line continuation)

```bash
curl -X POST "http://127.0.0.1:8000/api/update-log" ^
  -H "Content-Type: application/json" ^
  -d "{\"device_id\":1,\"heart_rate\":88,\"spo2\":98,\"lattitude\":-7.25,\"longitude\":112.75}"
```

---

### `POST /api/sos-trigger`

Inserts an SOS row into **`mountain_sos_signals`** and dispatches the **`SosSignalCreated`** broadcast event for realtime notifications (e.g. dashboard alarm).

#### Request body (JSON)

| Field | Required | Type | Notes |
|-------|----------|------|--------|
| `device_id` | Yes | integer | Must exist in `mountain_devices` |
| `lattitude` | Yes | number | Latitude *(field name matches current code spelling)* |
| `longitude` | Yes | number | Longitude |

#### Success response

- **HTTP 200** — `status`: `"success"`, `message`: `"SOS triggered successfully."`

#### Error responses

- **422** — Validation failed.
- **500** — Server error.

#### Example (`curl`, Windows line continuation)

```bash
curl -X POST "http://127.0.0.1:8000/api/sos-trigger" ^
  -H "Content-Type: application/json" ^
  -d "{\"device_id\":1,\"lattitude\":-7.25,\"longitude\":112.75}"
```

#### SOS and alarm integration notes

- Certain admin layouts subscribe to Pusher channel **`mountain-sos.{mountain_id}`** and play **`public/assets/sound/alert.mp3`** when an event is received.
- For realtime broadcasts: set **`BROADCAST_DRIVER=pusher`**, use valid Pusher credentials, and run a queue worker if broadcasts are queued in the future.
- Other methods in `SensorController` (**register/unregister** hiker) exist in code but are **not** registered in `routes/api.php` — add routes or use the web flow if you need non-UI registration.

---

## Additional SOS endpoint (not under `/api`)

For creating an SOS signal from the **admin dashboard** (authenticated session), the app exposes for example:

- **`POST /dashboard/sos/create`** — Different body (`booking_id`, `latitude`, `longitude`, optional `message`), with auth and admin role middleware.

Validation details are defined in `SOSMonitoringController::createSOSSignal`.

---

## Realtime (Pusher) and alarm sound

1. Configure `.env`: `BROADCAST_DRIVER=pusher`, set **`PUSHER_APP_*`** and **`VITE_PUSHER_APP_*`**, then run `php artisan config:clear` if config is cached.
2. Ensure **`public/assets/sound/alert.mp3`** is present (dashboard layout loads this file).
3. Browsers often block autoplay until the user **interacts** with the page once (the layout attempts to “unlock” audio on the first click).

---

## Testing

```bash
composer test
```

or:

```bash
php artisan test
```

---

## License

This project is built on **Laravel**, which is open-sourced under the [MIT license](https://opensource.org/licenses/MIT). Add your own project license here if it differs.
