# Environment Variable Reference — OmniTools Platform

This reference document defines all environment variables used by OmniTools across development, testing, and production deployments.

> **CRITICAL SECURITY NOTE:** Never commit real secrets, private keys, database passwords, or SMTP tokens to source control. Use `.env.example` as a template with placeholders only.

---

## 1. Core Application Configuration

| Variable | Purpose | Required / Optional | Production Recommended Value |
|:---|:---|:---:|:---|
| `APP_NAME` | Public brand and application title displayed in header, footer, and emails. | Required | `OmniTools` |
| `APP_ENV` | Application environment state (`local`, `testing`, `staging`, `production`). | Required | `production` |
| `APP_KEY` | 32-character AES-256 encryption key for sessions, cookies, and tokens. | **Required (Critical)** | Generated via `php artisan key:generate` |
| `APP_DEBUG` | Disables public stack traces and error details. | **Required (Critical)** | `false` |
| `APP_URL` | Canonical absolute URL prefix used for canonical tags, sitemaps, and redirects. | Required | `https://tools.example.com` |
| `APP_LOCALE` | Default language for incoming requests if not specified in path. | Required | `en` |
| `APP_FALLBACK_LOCALE` | Fallback language when a translation key is missing. | Required | `en` |
| `BCRYPT_ROUNDS` | Bcrypt hashing work factor for user and administrator passwords. | Optional | `12` |

---

## 2. Logging & Diagnostics

| Variable | Purpose | Required / Optional | Production Recommended Value |
|:---|:---|:---:|:---|
| `LOG_CHANNEL` | Logging strategy (`single`, `daily`, `stack`, `syslog`). | Required | `daily` |
| `LOG_LEVEL` | Minimum threshold for writing to error logs (`debug`, `info`, `warning`, `error`). | Required | `error` or `warning` |
| `LOG_STACK` | Stack channel driver if `LOG_CHANNEL=stack`. | Optional | `single` |

---

## 3. Database Configuration

| Variable | Purpose | Required / Optional | Production Recommended Value |
|:---|:---|:---:|:---|
| `DB_CONNECTION` | Database driver (`mysql` or `sqlite`). | Required | `mysql` |
| `DB_HOST` | Database host IP or loopback address. | Required (if MySQL) | `127.0.0.1` |
| `DB_PORT` | MySQL connection port. | Required (if MySQL) | `3306` |
| `DB_DATABASE` | Database name or absolute path if SQLite. | Required | `tools_platform` |
| `DB_USERNAME` | Restricted least-privilege database user. | Required (if MySQL) | `tools_user` |
| `DB_PASSWORD` | Strong database password (min 16 chars). | Required (if MySQL) | `[SECURE_DB_PASSWORD_PLACEHOLDER]` |

---

## 4. Session & Cookie Security

| Variable | Purpose | Required / Optional | Production Recommended Value |
|:---|:---|:---:|:---|
| `SESSION_DRIVER` | Session storage driver (`file`, `cookie`, `database`). | Required | `file` or `database` |
| `SESSION_LIFETIME` | Session expiration in minutes. | Optional | `120` |
| `SESSION_ENCRYPT` | Encrypt session payloads for added security. | Optional | `true` |
| `SESSION_PATH` | Path scope for session cookies. | Optional | `/` |
| `SESSION_DOMAIN` | Domain scope for session cookies. | Optional | `null` |
| `SESSION_SECURE_COOKIE`| Enforce `Secure` flag on cookies over HTTPS. | **Required (Critical)** | `true` |

---

## 5. Storage, Cache, & Queue

| Variable | Purpose | Required / Optional | Production Recommended Value |
|:---|:---|:---:|:---|
| `CACHE_STORE` | Cache driver (`file`, `array`, `database`). | Required | `file` |
| `QUEUE_CONNECTION` | Asynchronous queue driver (`sync`, `database`). | Required | `sync` |
| `FILESYSTEM_DISK` | Storage disk for local media assets. | Required | `public` or `local` |

---

## 6. Optional Analytics & Advertising Placeholders

These features remain inactive until configured in `.env` and toggled via the Admin Settings portal:

| Variable | Purpose | Required / Optional | Production Recommended Value |
|:---|:---|:---:|:---|
| `ANALYTICS_ENABLED` | Toggle global analytics script injection. | Optional | `false` |
| `ANALYTICS_PROVIDER` | Analytics engine (`google-analytics-4`). | Optional | `google-analytics-4` |
| `ANALYTICS_MEASUREMENT_ID` | Google Analytics 4 Measurement Tag ID. | Optional | `G-XXXXXXXXXX` |
| `ADSENSE_ENABLED` | Toggle Google AdSense advertising script. | Optional | `false` |
| `ADSENSE_CLIENT_ID` | Google AdSense Publisher Client ID. | Optional | `ca-pub-XXXXXXXXXXXXXXXX` |
| `ADSENSE_SLOT_HEADER` | Ad slot identifier for header placement. | Optional | `1234567890` |
| `ADSENSE_SLOT_FOOTER` | Ad slot identifier for footer placement. | Optional | `0987654321` |

---

## 7. Mail & Notifications (Optional)

| Variable | Purpose | Required / Optional | Production Recommended Value |
|:---|:---|:---:|:---|
| `MAIL_MAILER` | Mail driver (`smtp`, `log`, `mailgun`). | Optional | `smtp` |
| `MAIL_HOST` | Outbound SMTP server hostname. | Optional | `smtp.mailgun.org` |
| `MAIL_PORT` | SMTP port (typically 587 with TLS). | Optional | `587` |
| `MAIL_USERNAME` | SMTP authentication user. | Optional | `postmaster@example.com` |
| `MAIL_PASSWORD` | SMTP authentication password. | Optional | `[SECURE_SMTP_PASSWORD]` |
| `MAIL_ENCRYPTION` | Transport encryption (`tls` or `ssl`). | Optional | `tls` |
| `MAIL_FROM_ADDRESS` | Sender email address for system notices. | Optional | `support@example.com` |
| `MAIL_FROM_NAME` | Sender display name. | Optional | `"${APP_NAME}"` |
