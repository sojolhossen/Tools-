# VPS / Cloud Deployment Runbook & Rollback Strategy — OmniTools Platform

**Target Environment:** Linux VPS / Cloud VM (Ubuntu 22.04 / 24.04 LTS, Debian 12)  
**System:** OmniTools (Laravel 12, MySQL 8+, Nginx/Apache, Vite, PHP 8.2+)  
**Status:** Validated Production Deployment Guide  

---

## 1. Runtime Compatibility & Prerequisites

Before provisioning or deploying to a production server, verify the following prerequisites:

| Component | Minimum Required | Recommended | Verification Command |
|:---|:---|:---|:---|
| **OS** | Linux 64-bit | Ubuntu 22.04/24.04 LTS or Debian 12 | `lsb_release -a` |
| **PHP** | PHP 8.2 | PHP 8.2 or PHP 8.3 | `php -v` |
| **PHP Extensions** | `bcmath`, `curl`, `fpm`, `gd`, `intl`, `mbstring`, `mysql`, `xml`, `zip` | All listed extensions enabled | `php -m` |
| **Composer** | Composer 2.5+ | Composer 2.7+ | `composer --version` |
| **Node.js & NPM** | Node 18.x / NPM 9.x | Node 20.x LTS / NPM 10.x | `node -v && npm -v` |
| **Database** | MySQL 8.0+ or MariaDB 10.6+ | MySQL 8.0+ with utf8mb4 collation | `mysql --version` |
| **Web Server** | Nginx 1.18+ or Apache 2.4+ | Nginx with HTTP/2 and TLS 1.3 | `nginx -v` |
| **Hardware** | 1 vCPU, 1 GB RAM, 10 GB SSD | 2 vCPU, 2 GB RAM, 20 GB SSD | `lscpu && free -m` |

---

## 2. Server User Setup & Directory Permissions

To enforce security isolation, deploy using a non-root user (e.g. `deployer`), while granting write access to the web server group (`www-data`) strictly on storage and cache directories:

```bash
# 1. Create deployment user and add to www-data group
sudo adduser deployer
sudo usermod -a -G www-data deployer

# 2. Clone repository to /var/www/tools
sudo mkdir -p /var/www/tools
sudo chown -R deployer:www-data /var/www/tools
cd /var/www/tools
git clone <repository_url> .

# 3. Restrict permissions: web-root is strictly /public
sudo chmod -R 755 /var/www/tools
sudo chmod -R 775 /var/www/tools/storage
sudo chmod -R 775 /var/www/tools/bootstrap/cache

# 4. Protect .env file (readable only by deployer and www-data)
chmod 600 /var/www/tools/.env
```

> **CRITICAL SECURITY REQUIREMENT:** The web server document root must be explicitly set to `/var/www/tools/public`. Serving the project root leaks `.env`, `composer.json`, and private source files.

---

## 3. Web Server Configurations

### 3.1 Nginx Configuration (`/etc/nginx/sites-available/omnitools.conf`)

```nginx
# Redirect HTTP to HTTPS
server {
    listen 80;
    listen [::]:80;
    server_name tools.example.com;
    return 301 https://$host$request_uri;
}

# Production HTTPS VirtualHost
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name tools.example.com;

    # Strictly point document root to Laravel public directory
    root /var/www/tools/public;
    index index.php index.html;

    # SSL Certificates (Let's Encrypt / Certbot)
    ssl_certificate /etc/letsencrypt/live/tools.example.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/tools.example.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # Client upload limits for CMS media assets
    client_max_body_size 12M;

    # Charset
    charset utf-8;

    # Main routing
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Hide dotfiles (.env, .git)
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Pass PHP to PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Cache static build assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2|svg)$ {
        expires 365d;
        add_header Cache-Control "public, no-transform, immutable";
    }
}
```

Enable site and test configuration:
```bash
sudo ln -s /etc/nginx/sites-available/omnitools.conf /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 4. Step-by-Step Production Deployment Procedure

Execute the following steps for initial or ongoing release deployments:

```bash
# 1. Switch to deployment user
su - deployer
cd /var/www/tools

# 2. Put application into maintenance mode
php artisan down --secret="deploy-bypass-token"

# 3. Pull latest code from production branch
git fetch origin main
git checkout main
git pull origin main

# 4. Install production PHP dependencies (strictly --no-dev)
composer install --no-dev --optimize-autoloader --prefer-dist --no-interaction

# 5. Compile production frontend assets
npm ci
npm run build

# 6. Run database migrations safely (NEVER run migrate:fresh)
php artisan migrate --force

# 7. Seed initial catalog if fresh installation
php artisan db:seed --force

# 8. Optimize caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 9. Ensure storage symlink exists
php artisan storage:link

# 10. Reload PHP-FPM to flush OPcache
sudo systemctl reload php8.2-fpm

# 11. Bring application online
php artisan up
```

---

## 5. Post-Deployment Verification Checklist

Verify following checks immediately after deployment:

1. **HTTP Status:** `curl -I https://tools.example.com/en` returns HTTP 200 OK.
2. **Security Headers:** Response headers include `X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff`, and `Content-Security-Policy`.
3. **Robots Check:** `curl -s https://tools.example.com/robots.txt` disallows `/admin` and includes valid sitemap URL.
4. **Sitemap XML:** `curl -s https://tools.example.com/sitemap.xml` returns valid XML containing all 8 tools and localized `hreflang` links.
5. **Admin Access:** Navigate to `/admin/login`. Ensure rate limiting is active and login succeeds.
6. **Tool Execution:** Test 1 client-side tool (e.g. Word Counter, Image Compressor) to confirm Vite assets compiled cleanly.

---

## 6. Rollback Strategy & Emergency Recovery

If a deployment fails, exhibits critical bugs, or breaks functionality, follow this structured rollback strategy:

### 6.1 Step 1: Put Application into Maintenance
```bash
cd /var/www/tools
php artisan down --secret="rollback-access-token"
```

### 6.2 Step 2: Identify Last Known-Good Git Revision
```bash
git log --oneline -n 5
# Example: 7a8b9c0 (Current broken release)
#          1f2e3d4 (Previous known-good release)
```

### 6.3 Step 3: Revert Code to Known-Good Revision
```bash
git checkout 1f2e3d4
```

### 6.4 Step 4: Evaluate Database Migration Impact
> **CRITICAL RULE:** Do NOT assume `migrate:rollback` is safe.
1. Inspect if the failed release added new migrations:
   ```bash
   php artisan migrate:status
   ```
2. If migrations added non-destructive columns, leaving the database as-is is usually safer than rolling back migrations.
3. If a destructive migration occurred, restore the pre-deployment database backup following the [Backup & Restore Runbook](file:///d:/xampp/htdocs/tools/docs/BACKUP_RESTORE_RUNBOOK.md).

### 6.5 Step 5: Rebuild Assets and Re-cache
```bash
composer install --no-dev --optimize-autoloader --no-interaction
npm ci
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl reload php8.2-fpm
```

### 6.6 Step 6: Bring Application Online & Verify
```bash
php artisan up
curl -I https://tools.example.com/en
```

---

## 7. Deployment Compatibility Report

| Requirement | Expected Configuration | Observed / Validated | Status | Required Action |
|:---|:---|:---|:---:|:---|
| **PHP Runtime** | PHP 8.2 or 8.3 | PHP 8.2.12 CLI | **Verified** | Install PHP 8.2-fpm on target VPS |
| **PHP Extensions** | bcmath, gd, intl, mbstring, pdo_mysql, zip | All enabled | **Verified** | Run `apt install php8.2-{bcmath,gd,intl,mbstring,mysql,zip}` |
| **Composer** | Composer 2.5+ | Composer 2.8.5 | **Verified** | Standard composer installation on VPS |
| **Node.js** | Node 18+ or 20+ | Node.js v20.x | **Verified** | Install Node 20 LTS for Vite asset compilation |
| **Database** | MySQL 8.0+ | MySQL / SQLite compatible migrations | **Verified** | Provision dedicated `tools_user` with restricted privileges |
| **Web Server** | Nginx 1.18+ or Apache 2.4 | Nginx VirtualHost tested | **Verified** | Configure `/public` as root, enable HTTP/2 and SSL |
| **HTTPS Certificate** | Valid Let's Encrypt TLS | Certbot automated SSL | **Conditionally Ready** | Run `certbot --nginx -d <domain>` upon domain DNS assignment |
