# Maintenance & Operational Runbook — OmniTools Platform

**Target Audience:** DevOps Engineers, System Administrators, Lead Developers  
**System:** OmniTools Platform (Laravel 12, MySQL, Vite, Linux VPS)  
**Scope:** Controlled updates, migration policies, routine monitoring, incident response  

---

## 1. Controlled Dependency Maintenance Policy

To maintain long-term stability without introducing breaking changes or untested regressions, adhere to the following maintenance protocol:

### 1.1 Regular Vulnerability Auditing
Run security advisory audits bi-weekly or prior to any production deployment:

```bash
# Check Composer PHP dependencies
composer audit

# Check NPM JavaScript dependencies
npm audit
```

If an advisory is detected:
1. Review the CVE advisory severity and affected code paths.
2. If the vulnerable package is used in critical paths, update the specific minor/patch version:
   ```bash
   composer update vendor/package --with-dependencies
   ```
3. Run the automated test suite immediately (`php artisan test`) and verify the frontend build (`npm run build`).
4. Never perform major version upgrades (`composer update` without constraints) directly on production.

---

## 2. Database Migration Safety Rules

Database migrations must never cause accidental data loss or disruption to live traffic:

1. **Pre-Migration Snapshot**: Always take a database snapshot before running migrations:
   ```bash
   php artisan app:backup --only-db
   ```
2. **Never Run Destructive Commands**:
   - `migrate:fresh`, `migrate:reset`, or `migrate:refresh` are **strictly forbidden** in production. They permanently destroy tables, audit logs, and CMS content.
3. **Reversible Migrations**: Ensure all migrations define complete `down()` methods with foreign key constraint handling.
4. **Zero-Downtime Column Additions**: Add new columns as `nullable()` or with defaults so existing running code can continue operating during rollouts.

---

## 3. Routine Health Checks & Operational Diagnostics

### 3.1 Daily Automated Verification
Set up a daily automated cron job to verify application health:

```bash
# Check HTTP status of public site
curl -fsS -o /dev/null https://tools.example.com/en || echo "OmniTools Public Down!"

# Check Laravel internal health endpoint
curl -fsS -o /dev/null https://tools.example.com/up || echo "OmniTools Framework Down!"
```

### 3.2 Disk & Storage Maintenance
Ensure server disk space is monitored:
```bash
# Check disk usage
df -h

# Check backup storage folder size
du -sh /var/www/tools/storage/app/backups
```
The automated backup command (`php artisan app:backup --keep=7`) automatically purges backups older than 7 days, maintaining a stable disk footprint.

### 3.3 Log Rotation
Ensure Laravel error logs (`storage/logs/laravel-*.log`) do not grow unbounded:
- In `.env`, ensure `LOG_CHANNEL=daily`.
- Laravel retains 14 days of logs by default when using the `daily` channel.

---

## 4. Incident Response & Troubleshooting Playbook

### Scenario A: 500 Internal Server Error Reported
1. **Inspect Log:**
   ```bash
   tail -n 50 /var/www/tools/storage/logs/laravel-$(date +%Y-%m-%d).log
   ```
2. **Check Nginx Error Log:**
   ```bash
   tail -n 50 /var/log/nginx/error.log
   ```
3. **Verify File Permissions:**
   Ensure web server user owns storage:
   ```bash
   sudo chown -R deployer:www-data /var/www/tools/storage /var/www/tools/bootstrap/cache
   sudo chmod -R 775 /var/www/tools/storage /var/www/tools/bootstrap/cache
   ```
4. **Clear Corrupted Cache:**
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan config:cache
   ```

### Scenario B: Database Connection Refused
1. **Verify MySQL Service:**
   ```bash
   sudo systemctl status mysql
   ```
2. **Restart MySQL if Stopped:**
   ```bash
   sudo systemctl restart mysql
   ```
3. **Verify Credentials:**
   Test connection via CLI using `.env` credentials:
   ```bash
   mysql -u tools_user -p tools_platform
   ```

### Scenario C: High Server CPU Usage
Because all 8 client-side tools execute in the user's browser, high server CPU indicates an external crawl or brute-force attack on public routes.
1. Check Nginx access logs:
   ```bash
   tail -f /var/log/nginx/access.log
   ```
2. If a specific malicious IP is flooding requests, block the IP via UFW:
   ```bash
   sudo ufw insert 1 deny from 203.0.113.50 to any
   ```
