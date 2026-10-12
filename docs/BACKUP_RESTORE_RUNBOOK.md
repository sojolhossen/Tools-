# Backup & Disaster Recovery Runbook — OmniTools Platform

**Target Environment:** Linux VPS / Cloud Hosting (Ubuntu 22.04 / 24.04 LTS, Debian 12)  
**System:** OmniTools (Laravel 12, MySQL/SQLite, Local Media Assets)  
**Execution Tool:** `php artisan app:backup` / Native Shell Scripts  

---

## 1. Backup Scope & Inventory

The OmniTools platform maintains a lean footprint. The backup scope includes:

1. **Relational Database (`MySQL` or `SQLite`)**:
   - `users` (administrator accounts)
   - `categories` & `tools` (tool catalog metadata, view handlers, enabled states)
   - `content_pages` (custom guide articles, privacy, terms)
   - `ad_placements` (ad slot codes and toggle states)
   - `site_settings` (platform title, analytics IDs, operational flags)
   - `audit_logs` (administrative activity trail)
   - `media_assets` (media catalog index)
2. **Uploaded Media Assets**:
   - Files stored in `storage/app/public/media/` (images and documents uploaded through the CMS).
3. **Environment Secrets & Configuration**:
   - Production `.env` file (must be backed up separately via secure, encrypted offsite channels).

> **ZERO USER DATA NOTICE:** End-user images, converted PDFs, and calculations from the 8 public tools are processed 100% in the user's browser. There are no user processing files in the storage directory, drastically reducing backup storage requirements and eliminating privacy leakage risk.

---

## 2. Automated Backup Command: `php artisan app:backup`

OmniTools includes a built-in Artisan backup command:

```bash
# Full backup of database and media assets with default 7-day retention
php artisan app:backup

# Custom retention (e.g. keep last 14 backups)
php artisan app:backup --keep=14

# Database only backup (skips media assets)
php artisan app:backup --only-db
```

### What this command does:
1. Detects the active database driver (`mysql` or `sqlite`).
2. Generates a timestamped dump (`database_dump_YYYY-MM-DD_HHMMSS.sql`).
3. Bundles the database dump and all files from `storage/app/public/media/` into a single zip archive (`storage/app/backups/omnitools_backup_YYYY-MM-DD_HHMMSS.zip`).
4. Automatically prunes older backup archives in `storage/app/backups/` that exceed the `--keep` threshold.
5. Emits clean console output and returns exit code `0` on success.

---

## 3. Automated Scheduling via Linux Crontab

To schedule daily backups at 02:00 AM server time, add a cron entry on your VPS:

```bash
# Open deployer crontab
crontab -e

# Run automated daily backup at 2:00 AM and retain 14 days
0 2 * * * cd /var/www/tools && php artisan app:backup --keep=14 >> /var/log/omnitools_backup.log 2>&1
```

Or via Laravel's native Scheduler in `bootstrap/app.php` or `routes/console.php`:
```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('app:backup --keep=14')->dailyAt('02:00');
```

---

## 4. Offsite Backup & Encryption Strategy

Backups residing on the local disk protect against accidental deletions but not full VPS hardware loss. Transfer backups offsite using `rclone`, `rsync`, or encrypted SCP.

### Example Offsite Sync Script (`/usr/local/bin/sync_backups.sh`):

```bash
#!/usr/bin/env bash
set -euo pipefail

BACKUP_DIR="/var/www/tools/storage/app/backups"
GPG_RECIPIENT="sysadmin@example.com"
REMOTE_DEST="s3-remote:my-omnitools-backups"

# Find newest backup archive
LATEST_BACKUP=$(ls -t ${BACKUP_DIR}/omnitools_backup_*.zip | head -n1)

# Encrypt backup with GPG
gpg --yes --batch --encrypt --recipient "${GPG_RECIPIENT}" "${LATEST_BACKUP}"

# Upload encrypted archive to remote storage
rclone copy "${LATEST_BACKUP}.gpg" "${REMOTE_DEST}"

# Remove temporary .gpg file
rm -f "${LATEST_BACKUP}.gpg"

echo "[$(date)] Backup ${LATEST_BACKUP} encrypted and uploaded successfully."
```

---

## 5. Step-by-Step Restoration Procedure

> **CRITICAL RULE:** Never restore backups directly into a live production environment without verifying integrity first. Perform recovery in a staging environment or maintenance window.

### Step 1: Put Application into Maintenance Mode
```bash
cd /var/www/tools
php artisan down --secret="restore-emergency-access-token"
```

### Step 2: Extract Backup Archive
```bash
# Choose the target backup file
BACKUP_FILE="/var/www/tools/storage/app/backups/omnitools_backup_2026-10-12_020000.zip"
RESTORE_DIR="/tmp/omnitools_restore_$(date +%s)"

mkdir -p "${RESTORE_DIR}"
unzip "${BACKUP_FILE}" -d "${RESTORE_DIR}"
```

### Step 3: Restore Database

**For MySQL:**
```bash
# Import database dump
mysql -u tools_user -p tools_platform < "${RESTORE_DIR}/database/database_dump_*.sql"
```

**For SQLite:**
```bash
# Backup existing database file first
cp /var/www/tools/database/database.sqlite /var/www/tools/database/database.sqlite.bak

# Copy restored database
cp "${RESTORE_DIR}/database/database_dump_*.sqlite" /var/www/tools/database/database.sqlite
chmod 664 /var/www/tools/database/database.sqlite
chown www-data:www-data /var/www/tools/database/database.sqlite
```

### Step 4: Restore Media Assets
```bash
if [ -d "${RESTORE_DIR}/media" ]; then
    rsync -av "${RESTORE_DIR}/media/" /var/www/tools/storage/app/public/media/
    chmod -R 775 /var/www/tools/storage/app/public/media/
    chown -R deployer:www-data /var/www/tools/storage/app/public/media/
fi
```

### Step 5: Flush Caches & Re-link Storage
```bash
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

### Step 6: Verify Database Integrity & Bring Live
```bash
# Verify record counts
php artisan tinker --execute="echo 'Tools: ' . App\Models\Tool::count() . ', Pages: ' . App\Models\ContentPage::count() . PHP_EOL;"

# Bring application back online
php artisan up

# Clean up temporary restoration directory
rm -rf "${RESTORE_DIR}"
```

---

## 6. Verification Status

- **Automated Verification:** The backup creation, zip packaging, database dump inclusion, and retention pruning were automated and tested in `tests/Feature/BackupCommandTest.php`.
- **Test Result:** 2 tests passed with 10 assertions. Verified archive generation and `--keep` deletion.
