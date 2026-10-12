# Google Search Console & Bing Webmaster Tools Setup Runbook — ToolZeno

**Domain:** `https://toolzeno.site/`  
**Brand:** ToolZeno  
**Production Origin:** `https://toolzeno.site`  
**Sitemap Location:** `https://toolzeno.site/sitemap.xml`  
**Document Purpose:** Exact, ethical, step-by-step setup and verification procedures for search engine webmaster properties without fabricating verification tokens or DNS records.

---

## 1. Google Search Console (GSC) Setup

### 1.1 Property Type Recommendation
Google Search Console provides two property types:
1. **Domain Property (`toolzeno.site`):** **(Recommended)** Aggregates all subdomains (`toolzeno.site`, `www.toolzeno.site`), protocols (`http://`, `https://`), and URL paths under a single unified profile. Requires DNS TXT verification.
2. **URL-Prefix Property (`https://toolzeno.site/`):** Matches only URLs with the exact scheme and host. Useful as a secondary companion property for specific URL inspection and hreflang validation.

### 1.2 DNS Verification Procedure (AmarHoster / cPanel DNS)
1. Log in to [Google Search Console](https://search.google.com/search-console).
2. Click **Add Property** and select the **Domain** option. Enter `toolzeno.site`.
3. Google Search Console will generate a unique TXT verification record in the format:
   ```text
   google-site-verification=<UNIQUE_HASH_GENERATED_BY_GOOGLE>
   ```
   *(Note: Do not invent or reuse a hash. Copy the exact record provided in your GSC dashboard.)*
4. Log in to the domain registrar/hosting DNS manager (AmarHoster / cPanel Zone Editor).
5. Add a new DNS Record:
   - **Type:** `TXT`
   - **Name / Host:** `@` or `toolzeno.site.`
   - **TTL:** `3600` (or default 14400)
   - **Value / Record:** Paste the exact `google-site-verification=...` string from Google.
6. Save the record and wait for DNS propagation (typically 2 to 30 minutes).
7. Return to Google Search Console and click **Verify**.

### 1.3 Alternative Verification for URL-Prefix Property (HTML File / Meta Tag)
If DNS access is temporarily delayed, verify `https://toolzeno.site/` using the HTML tag or file method:
- **HTML File:** Download the verification file (e.g., `google<unique_id>.html`) and place it inside the Laravel `public/` directory (`d:/xampp/htdocs/tools/public/google<unique_id>.html`). Nginx/Apache will serve it directly.
- **Blade Layout Tag:** Alternatively, place the verification tag in `resources/views/layouts/app.blade.php` in the `<head>` section via site settings.

### 1.4 Production XML Sitemap Submission
1. In the left navigation of GSC, navigate to **Indexing > Sitemaps**.
2. Under "Add a new sitemap", enter:
   ```text
   sitemap.xml
   ```
   (Full URL submitted: `https://toolzeno.site/sitemap.xml`).
3. Click **Submit**.
4. Confirm status displays **Success** with valid URL counts discovered across English (`/en/...`) and Bangla (`/bn/...`).

### 1.5 Initial URL Inspection & Crawl Monitoring
1. Inspect the key canonical URLs using the GSC search bar:
   - Homepage: `https://toolzeno.site/en`
   - All Tools Directory: `https://toolzeno.site/en/tools`
   - Initial Core Tools:
     - `https://toolzeno.site/en/tools/word-counter`
     - `https://toolzeno.site/en/tools/case-converter`
     - `https://toolzeno.site/en/tools/percentage-calculator`
     - `https://toolzeno.site/en/tools/json-formatter`
     - `https://toolzeno.site/en/tools/qr-generator`
     - `https://toolzeno.site/en/tools/image-compressor`
     - `https://toolzeno.site/en/tools/image-resizer`
     - `https://toolzeno.site/en/tools/image-to-pdf`
2. Click **Test Live URL** to confirm:
   - HTTP 200 OK status.
   - Googlebot can render HTML, CSS, and JS bundles.
   - Self-referencing canonical tag is detected properly.
   - JSON-LD schemas (`WebSite`, `WebApplication`, `BreadcrumbList`) are recognized without warnings.
3. Click **Request Indexing** for the primary homepage and directory to initiate discovery.

### 1.6 Ongoing Quality & Performance Monitoring Checklist
- **Coverage / Page Indexing:** Monitor "Indexed" vs. "Not indexed" reasons (ensure no "Soft 404" or "Duplicate without user-selected canonical").
- **Hreflang Monitoring:** Confirm zero hreflang annotation conflicts between `/en/...` and `/bn/...` endpoints.
- **Security & Manual Actions:** Verify clean bill of health with zero issues detected under **Security & Manual Actions**.
- **Search Performance Report:** Track real impressions, organic queries, CTR, and average position as Google indexes pages over the first 30 days.

---

## 2. Bing Webmaster Tools Setup

### 2.1 Fast-Track GSC Import
1. Navigate to [Bing Webmaster Tools](https://www.bing.com/webmasters).
2. Click **Add a site** and select **Import from Google Search Console**.
3. Sign in with the verified Google account. Bing instantly verifies domain ownership and imports verified properties without additional DNS modifications.

### 2.2 Manual Bing Verification (Alternative)
If manual setup is preferred:
1. Add `https://toolzeno.site`.
2. Add a CNAME or DNS TXT record generated by Bing into AmarHoster cPanel Zone Editor.
3. Submit sitemap: `https://toolzeno.site/sitemap.xml`.

---

## 3. Crawl Rate & Diagnostic Rules
- **Robots.txt Location:** `https://toolzeno.site/robots.txt`
  - Allows public crawling of `/en/...`, `/bn/...`, `/assets/...`, and Vite bundles.
  - Prohibits crawling of `/admin/`, `/up` health checks, and API endpoints.
  - Declares canonical sitemap: `Sitemap: https://toolzeno.site/sitemap.xml`.
- **Search Engine Courtesy:** Keep tools executed client-side in the browser to maintain ultra-low TTFB (<150ms) and minimal server load during high-frequency search engine crawl passes.
