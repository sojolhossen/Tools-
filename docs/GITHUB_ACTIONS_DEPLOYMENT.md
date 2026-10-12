# GitHub Actions CI/CD Deployment Guide — ToolZeno.site

**Target Workflow:** `.github/workflows/deploy.yml`  
**Application:** ToolZeno (`https://toolzeno.site`)  
**Stack:** Laravel 12, PHP 8.2+, MySQL, Blade, Tailwind CSS, Vite, Alpine.js  
**Target Hosting:** AmarHoster (cPanel / Shared Hosting / VPS)  
**Deployment Method:** SSH & Rsync (with pre-compiled production build artifacts)

---

## 1. Overview & Architecture

The GitHub Actions workflow implements an isolated, two-stage deployment pipeline:

1. **Stage 1 (`validate-and-build`):**
   - Runs on clean `ubuntu-latest` runner.
   - Sets up PHP 8.2 and Node.js 20 with package caches.
   - Installs Composer and NPM dependencies reproducibly via lockfiles.
   - Compiles production frontend assets via Vite (`npm run build`).
   - Executes the full automated test suite (`php artisan test`).
   - Prunes development packages (`composer install --no-dev`).
   - Packages a clean production payload (omitting `.git`, `.github`, `tests`, `docs`, `node_modules`, and `.env`).
2. **Stage 2 (`deploy-production`):**
   - Triggers on push to `main` or manual `workflow_dispatch`.
   - Runs with strict concurrency control (`concurrency: production-deployment`) to prevent collisions.
   - Verifies required GitHub Secrets before initiating transfer.
   - Authenticates via SSH using a dedicated deployment key.
   - Validates server host authenticity via strict `known_hosts` checking (never bypassed).
   - Deploys files using `rsync` with `--exclude='.env'` and storage exclusions (preserving production configuration, logs, and user uploads).
   - Optimizes Laravel caches on the remote host (`config:cache`, `route:cache`, `view:cache`).
   - Executes database migrations **only** when explicitly requested (`run_migrations: true`).

---

## 2. Required GitHub Repository Secrets

Configure the following secrets in your GitHub repository under **Settings > Secrets and variables > Actions > New repository secret**:

| Secret Name | Required? | Example Value | Description |
| :--- | :--- | :--- | :--- |
| `DEPLOY_HOST` | **Yes** | `198.51.100.25` or `server.amarhoster.com` | Server hostname or IP address of your AmarHoster account. |
| `DEPLOY_USER` | **Yes** | `toolzeno` (or your cPanel username) | SSH user account with write permissions to the deployment folder. |
| `DEPLOY_SSH_KEY` | **Yes** | `-----BEGIN OPENSSH PRIVATE KEY-----...` | OpenSSH private key used for authentication (ed25519 or RSA). |
| `DEPLOY_PATH` | **Yes** | `/home/username/toolzeno` | Absolute path on the server where the Laravel project resides. |
| `DEPLOY_SSH_KNOWN_HOSTS` | **Yes** | `198.51.100.25 ssh-ed25519 AAAAC3NzaC...` | Exact host key signature output from `ssh-keyscan`. |
| `DEPLOY_PORT` | *Optional* | `22` (or custom cPanel SSH port e.g. `2222`) | SSH port number (defaults to `22` if omitted). |

> [!WARNING]
> **Zero Credential Exposure:** Never put your SSH private key or server password directly into `.github/workflows/deploy.yml` or source files. Only reference them via `${{ secrets.<NAME> }}`.

---

## 3. Step-by-Step Setup Runbook

### Step 3.1: Generate an Isolated SSH Key Pair
Generate a dedicated SSH key pair on your local computer (or server):

```bash
ssh-keygen -t ed25519 -C "github-actions-deploy@toolzeno.site" -f toolzeno_deploy_key
```

This creates two files:
- `toolzeno_deploy_key` (Private key — copy full content into GitHub secret `DEPLOY_SSH_KEY`).
- `toolzeno_deploy_key.pub` (Public key — must be authorized on AmarHoster).

### Step 3.2: Authorize Public Key on AmarHoster
1. Log in to your **cPanel** on AmarHoster.
2. Navigate to **Security > SSH Access** (or **Terminal**).
   *(If SSH Access is missing, open a quick ticket with AmarHoster support: "Please enable SSH Terminal access for my cPanel account.")*
3. Click **Manage SSH Keys > Import Key**.
4. Paste the content of `toolzeno_deploy_key.pub` into the "Public Key" box and name it `toolzeno_deploy_key`.
5. Click **Import**, then click **Manage > Authorize** to activate the key.
   *(Or append the public key directly to `~/.ssh/authorized_keys` with permissions `chmod 600 ~/.ssh/authorized_keys`).*

### Step 3.3: Obtain the `DEPLOY_SSH_KNOWN_HOSTS` Value
Run `ssh-keyscan` on your local terminal against your AmarHoster server IP/host:

```bash
# If using standard port 22:
ssh-keyscan -H 198.51.100.25

# If using a custom port (e.g., 2222):
ssh-keyscan -p 2222 -H 198.51.100.25
```

Copy the entire output line (e.g. `198.51.100.25 ssh-ed25519 AAAAC...`) and paste it as the `DEPLOY_SSH_KNOWN_HOSTS` secret in GitHub.

---

## 4. AmarHoster & Shared Hosting Architecture Best Practices

On cPanel shared hosting, the web-accessible directory is typically `/home/<user>/public_html`. A standard Laravel project structure should be organized as follows:

### Recommended Setup (Folder Outside `public_html`)
1. **Core Laravel Files:** Placed in `/home/<user>/toolzeno` (the `DEPLOY_PATH`).
2. **Web Document Root Options:**
   - **Option A (Symlink — Recommended):**
     If AmarHoster permits symlinks, remove the default `public_html` directory and symlink it to `toolzeno/public`:
     ```bash
     cd /home/<user>
     rm -rf public_html
     ln -s /home/<user>/toolzeno/public public_html
     ```
   - **Option B (Separate Document Root via cPanel):**
     If `toolzeno.site` is added as an Addon Domain or Subdomain in cPanel, set its Document Root directly to `toolzeno/public`.
   - **Option C (`index.php` Proxy):**
     If symlinks are disallowed by server security, configure `public_html/index.php` to load autoload and bootstrap from `/home/<user>/toolzeno/`:
     ```php
     require __DIR__.'/../toolzeno/vendor/autoload.php';
     $app = require_once __DIR__.'/../toolzeno/bootstrap/app.php';
     ```

### Production `.env` Management
- The GitHub workflow **intentionally excludes `.env`** so your production database credentials, APP_KEY, and mail settings are never overwritten.
- Create `/home/<user>/toolzeno/.env` once manually on the server with:
  ```dotenv
  APP_NAME=ToolZeno
  APP_ENV=production
  APP_KEY=base64:YOUR_PRODUCTION_GENERATED_KEY
  APP_DEBUG=false
  APP_URL=https://toolzeno.site

  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=cpaneluser_toolzeno
  DB_USERNAME=cpaneluser_dbuser
  DB_PASSWORD=YOUR_STRONG_PASSWORD
  ```

---

## 5. Running & Monitoring Deployments

### 5.1 Automatic Deployment (Push to `main`)
Whenever you push changes or merge a pull request to `main`:
1. GitHub Actions triggers `Deploy ToolZeno`.
2. The `validate-and-build` job builds Vite assets and runs all 93 automated tests.
3. If tests pass, the `deploy-production` job deploys the build payload and refreshes production caches.
4. If tests fail, deployment is automatically halted.

### 5.2 Manual Deployment (`workflow_dispatch`)
To trigger a deployment manually:
1. Go to your repository on GitHub.
2. Click **Actions > Deploy ToolZeno**.
3. Click **Run workflow**.
4. You can configure:
   - **dry_run** (checkbox): Simulates deployment via `rsync --dry-run` to inspect modified files without changing server state.
   - **run_migrations** (checkbox): Executes `php artisan migrate --force` if schema changes were added.

---

## 6. Pre-Deployment Checklist for AmarHoster

Before triggering the first deployment, verify:

- [ ] SSH Access is enabled in AmarHoster cPanel.
- [ ] SSH key pair is generated and authorized in `~/.ssh/authorized_keys`.
- [ ] Remote directory `/home/<user>/toolzeno` is created with permissions `755`.
- [ ] Production `.env` exists in `/home/<user>/toolzeno/.env` with `APP_DEBUG=false` and production MySQL credentials.
- [ ] MySQL database and user are created in cPanel MySQL Databases with full privileges.
- [ ] GitHub repository secrets (`DEPLOY_HOST`, `DEPLOY_USER`, `DEPLOY_SSH_KEY`, `DEPLOY_PATH`, `DEPLOY_SSH_KNOWN_HOSTS`) are filled.
- [ ] Document root points correctly to the `public/` directory so `.env` is not publicly accessible.

---

## 7. Troubleshooting & Failure Recovery

| Issue / Error | Likely Cause | Solution |
| :--- | :--- | :--- |
| `Required GitHub Secrets are missing` | One or more secrets are unconfigured. | Check **Settings > Secrets and variables > Actions** and populate all required secrets. |
| `Host key verification failed` | Server host key in `DEPLOY_SSH_KNOWN_HOSTS` is missing or changed. | Re-run `ssh-keyscan -H <HOST>` and update the `DEPLOY_SSH_KNOWN_HOSTS` secret. |
| `Permission denied (publickey)` | Public key not authorized in cPanel SSH. | Confirm `toolzeno_deploy_key.pub` is imported and authorized in cPanel SSH Keys. |
| `Remote deployment path does not exist` | `DEPLOY_PATH` does not match the actual server path. | Log in to cPanel File Manager / Terminal and run `pwd` to verify the absolute path (e.g. `/home/username/toolzeno`). |
| `Vite assets missing on live site` | Build artifact not copied or document root mismatch. | Confirm your web root points to `public/` where `build/manifest.json` and assets are transferred. |
| `HTTP 500 Server Error after deploy` | PHP version mismatch or storage permissions. | Check cPanel Select PHP Version (ensure PHP 8.2+). Verify `storage` and `bootstrap/cache` are writable (`chmod -R 775 storage bootstrap/cache`). |

---

## 8. Unresolved Blockers & Host-Specific Dependencies

The following items are external prerequisites that require access to your AmarHoster account:
1. **AmarHoster SSH Authorization:** SSH must be explicitly enabled and authorized for your cPanel account.
2. **Server Absolute Path:** The exact home directory path (e.g. `/home/cpaneluser/toolzeno`) must be verified from your cPanel dashboard.
3. **DNS & SSL Certificate:** `toolzeno.site` must point to the AmarHoster nameservers/IP with active SSL (Let's Encrypt / AutoSSL).
