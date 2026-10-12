# Threat Model — OmniTools Platform

**Framework:** STRIDE (Spoofing, Tampering, Repudiation, Information Disclosure, Denial of Service, Elevation of Privilege)  
**System:** OmniTools Free Online Utilities Platform  
**Target Architecture:** Laravel 12 Backend, MySQL Database, Blade Views, Browser-Side JavaScript Tools, VPS Deployment  

---

## 1. System Assets

1. **System & Administrator Credentials**:
   - Administrative login email and bcrypt-hashed passwords.
   - Database connection credentials and encryption keys (`APP_KEY`).
2. **CMS & Tool Catalog Metadata**:
   - Tool directory records, category taxonomies, and public educational guide pages.
   - System settings (`SiteSetting` table) controlling analytics IDs, site titles, and ad placements.
3. **Audit Trail & System Logs**:
   - Administrative operation logs (`audit_logs` table) and server error logs (`storage/logs/laravel.log`).
4. **End-User Privacy & Upload Integrity**:
   - Zero-server data principle: User images, text, and documents must never be stored on the server without explicit informed consent.
5. **Compute & Memory Availability**:
   - Server CPU and RAM availability to serve public pages and prevent denial of service.

---

## 2. Trust Boundaries & Data Flow

```
+-------------------------------------------------------------------+
|                        Untrusted Zone                             |
|  - Anonymous Internet Users & Public Crawlers                     |
+-------------------------------------------------------------------+
                                  |
                                  | HTTPS (Port 443)
                                  v
+-------------------------------------------------------------------+
|                      DMZ / Reverse Proxy                          |
|  - Nginx Web Server (TLS Termination, Rate Limiting, Static Files)|
+-------------------------------------------------------------------+
                                  |
                                  | FastCGI Socket
                                  v
+-------------------------------------------------------------------+
|                   Application Trust Boundary                      |
|  - Laravel Application Runtime (PHP-FPM)                          |
|  - SecurityHeadersMiddleware (CSP, X-Frame-Options, HSTS)         |
|  - Public Routes: Home, Tools, Categories, Informational Pages    |
|  - Client-Side Tools Execution: Isolated in Browser Canvas/DOM    |
|  - Admin Middleware: Auth Guard, Role Check, Session Regeneration |
+-------------------------------------------------------------------+
                                  |
                                  | Internal Loopback / TCP
                                  v
+-------------------------------------------------------------------+
|                     Internal Data Storage                         |
|  - MySQL Database (User accounts, tools catalog, audit logs)      |
|  - Local Filesystem (`storage/app/backups`, `storage/app/public`) |
+-------------------------------------------------------------------+
```

---

## 3. STRIDE Threat Analysis & Mitigations

### 3.1 Spoofing (Identity Compromise)
- **Threat**: An attacker attempts to forge credentials or hijack administrator sessions to access the admin portal.
- **Mitigations**:
  - Administrative routes protected by `auth` guard and `AdminMiddleware`.
  - Passwords hashed using bcrypt.
  - Session ID regenerated upon successful authentication (`$request->session()->regenerate()`).
  - Session cookies configured with `HttpOnly`, `SameSite=Lax`, and `Secure` over HTTPS.
  - Password reset flows do not reveal account existence.

### 3.2 Tampering (Data Modification)
- **Threat**: An attacker attempts to tamper with tool parameters, inject malicious SQL, or alter CMS content with malicious JavaScript.
- **Mitigations**:
  - Eloquent ORM and parameterized queries completely prevent SQL injection.
  - `SafeContentRenderer` sanitizes all admin HTML through an allowlist before rendering.
  - CSRF tokens validated on all state-changing HTTP POST/PUT/DELETE requests.
  - Public registration disabled to prevent unauthorized account creation.

### 3.3 Repudiation (Denial of Actions)
- **Threat**: An administrator denies making configuration changes or modifying site settings.
- **Mitigations**:
  - `AuditLogger` automatically records all administrative mutations (action, user ID, IP address, user agent, timestamp, before/after attributes).
  - Sensitive parameters (passwords, API tokens) are automatically redacted from audit logs.

### 3.4 Information Disclosure (Leakage of Confidential Data)
- **Threat**: Stack traces, database credentials, server paths, or private user files leaked to visitors or search engines.
- **Mitigations**:
  - `APP_DEBUG=false` enforced on production.
  - Generic error messages for failed logins and unhandled exceptions.
  - Public tools process all images, PDF conversions, and text transformations **100% in the user's browser**. No user data is sent to the backend.
  - Security headers disallow caching of sensitive admin responses and inject `X-Robots-Tag: noindex, nofollow` on `/admin*`.
  - `.env` and `storage/app/backups/` are strictly outside the public web root.

### 3.5 Denial of Service (DoS / Resource Exhaustion)
- **Threat**: An attacker floods the login portal or submits enormous image files to exhaust server memory and CPU.
- **Mitigations**:
  - Rate limiting on login endpoints: maximum 5 attempts per minute per IP.
  - Rate limiting on public contact form: maximum 5 submissions per minute per IP.
  - Heavy image and PDF operations occur **in the user's browser**; server CPU/RAM usage is virtually zero during tool operations.
  - Administrative media uploads capped at 10MB per file with server-side validation.

### 3.6 Elevation of Privilege
- **Threat**: A normal visitor or authenticated user tries to access `/admin/tools`, `/admin/settings`, or execute administrative actions.
- **Mitigations**:
  - `AdminMiddleware` checks `is_admin === true` on every administrative request.
  - Unauthorized requests are rejected with HTTP 403 Forbidden.
  - User model prevents mass assignment of privileged attributes (`is_admin`).
  - Account provisioning restricted to the server console CLI (`php artisan admin:create`).

---

## 4. Threat Model Summary Table

| Threat ID | Category | Target Asset | Severity | Mitigation Status |
|:---|:---|:---|:---|:---|
| TM-01 | Elevation of Privilege | Admin Portal | High | Verified via `AdminMiddleware` & unit tests |
| TM-02 | Spoofing | Admin Login | High | Mitigated via RateLimiter & session regeneration |
| TM-03 | Tampering | CMS Content | High | Mitigated via `SafeContentRenderer` allowlist |
| TM-04 | Information Disclosure | User Files | Critical | Mitigated: 100% browser-side tool execution |
| TM-05 | Information Disclosure | Database & Secrets | Critical | Mitigated: Document root strictly `/public`, `.env` locked |
| TM-06 | Denial of Service | Web Server Resources | Medium | Mitigated: Client-side tool compute & rate limits |
