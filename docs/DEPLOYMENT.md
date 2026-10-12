# Free Online Tools Platform (OmniTools) — VPS & Cloud Deployment Guide

This document specifies the exact environment requirements, server configuration, database migration precautions, and operational procedures for deploying OmniTools to a Linux VPS or cloud instance (Ubuntu 22.04 / 24.04 LTS, Debian 12, or comparable distribution).

---

## 1. System Requirements & PHP Extensions

- **Operating System**: Linux (Ubuntu 22.04/24.04 LTS or Debian 12 recommended)
- **PHP Version**: `PHP 8.2` or `PHP 8.3`
- **Required PHP Extensions**:
  - `php-fpm` (FastCGI Process Manager)
  - `php-cli` (Artisan commands)
  - `php-mysql` or `php-sqlite3` (PDO drivers)
  - `php-mbstring` (Unicode handling for Bangla and multi-byte text)
  - `php-xml` & `php-curl` (Sitemaps and outbound API requests)
  - `php-bcmath` (High-precision percentage and financial calculations)
  - `php-gd` or `php-imagick` (Image processing and thumbnail generation)
  - `php-zip` (Archive utilities and package management)
  - `php-intl` (Internationalization and localized number formatting)
- **Web Server**: Nginx (preferred) or Apache 2.4 with `mod_rewrite` enabled
- **Database**: MySQL 8.0+ / MariaDB 10.6+ (or SQLite 3 for small standalone nodes)
- **Node.js**: Node 18+ or 20+ LTS with `npm` (required for Vite asset compilation)
- **Composer**: Composer 2.6+

---

## 2. Directory Structure & Permissions

In production, the web server user (typically `www-data` or `nginx`) must own the writable storage paths, while code remains owned by a deployment user.

```bash
# Set deployment user ownership
sudo chown -R deployer:www-data /var/www/tools

# Grant write permissions only to storage and bootstrap cache
sudo chmod -R 775 /var/www/tools/storage
sudo chmod -R 775 /var/www/tools/bootstrap/cache

# Ensure .env is protected (read-only for deployer and www-data)
chmod 600 /var/www/tools/.env
```

> **CRITICAL SECURITY NOTE**: Never point the web server root to `/var/www/tools`. The web server root must **strictly** point to `/var/www/tools/public`. Exposing the root folder leaks `.env`, `composer.json`, and application source code.

---

## 3. Web Server Configuration

### Nginx VirtualHost (Recommended)

Save as `/etc/nginx/sites-available/tools.conf`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name example.com www.example.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name example.com www.example.com;

    # Document root strictly pointing to /public
    root /var/www/tools/public;
    index index.php index.html;

    # SSL certificates (Let's Encrypt / Certbot)
    ssl_certificate /etc/letsencrypt/live/example.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/example.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Max upload limit for client-side uploads (e.g., image compressor / media library)
    client_max_body_size 20M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Hide hidden files (.env, .git, etc.)
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Pass PHP scripts to FastCGI
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2|svg)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
    }
}
```

---

## 4. Production Environment Configuration (`.env`)

Configure production variables before launching:

```ini
APP_NAME=OmniTools
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://example.com

LOG_CHANNEL=daily
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tools_platform
DB_USERNAME=tools_user
DB_PASSWORD=YOUR_STRONG_DATABASE_PASSWORD

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true

QUEUE_CONNECTION=sync
CACHE_STORE=file

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="support@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 5. Deployment Step-by-Step

Execute the following commands upon initial or release deployment:

```bash
# 1. Pull latest code
git pull origin main

# 2. Install production PHP dependencies without dev packages
composer install --no-dev --optimize-autoloader --prefer-dist --no-interaction

# 3. Compile frontend production assets with Vite
npm ci
npm run build

# 4. Run database migrations safely (NEVER run migrate:fresh)
php artisan migrate --force

# 5. Seed default catalog if initial setup (safe updateOrCreate)
php artisan db:seed --force

# 6. Optimize route, config, and view caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Create storage symlink
php artisan storage:link

# 8. Reload PHP-FPM to flush OPcache
sudo systemctl reload php8.2-fpm
```

---

## 6. Initial Administrator Provisioning

Administrator accounts are never registered via the web interface. Provision an administrator safely via the CLI command:

```bash
php artisan admin:create --name="Lead Admin" --email="admin@example.com" --password="YourSuperStrongPassword123!"
```

If an administrator account already exists, the command refuses execution to prevent accidental overrides. Use `--force` only if authorized to reset an existing administrator account:

```bash
php artisan admin:create --name="Lead Admin" --email="admin@example.com" --password="NewPassword123!" --force
```

---

## 7. Database Backup & Migration Precautions

### Backup Strategy
Always create a pre-migration snapshot before running database migrations on production:

```bash
# Create timestamped mysqldump backup
mysqldump -u tools_user -p tools_platform > /var/backups/db_tools_pre_migration_$(date +%F_%T).sql
```

### Migration Safety Rules
1. **Never use `migrate:fresh` or `migrate:reset`** on production. This permanently destroys user records, CMS pages, and audit logs.
2. All migrations in this repository use reversible `up()` and `down()` methods with foreign key safety checks.
3. Test all migrations against a staging copy before deploying to the primary database.
