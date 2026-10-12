# Initial SEO Audit Report & URL Inventory — ToolZeno

**Target Website:** [https://toolzeno.site/](https://toolzeno.site/)  
**Brand:** ToolZeno  
**Business:** Free Online Tools Platform  
**Architecture:** Laravel 12, Blade, Tailwind CSS, Vite, SQLite (Dev/Test) / MySQL (Production)  
**Supported Languages:** English (`en`, default) and Bangla (`bn`)  
**Audit Timestamp:** October 2026  
**Auditor:** Technical SEO Engineer & Laravel Architecture Specialist  

---

## 1. Executive Summary

This initial audit establishes the technical baseline of the ToolZeno platform prior to technical and on-page SEO optimization. The platform currently has a functioning, high-performance foundation with 8 client-side browser utilities, localized routing (`/en/...` and `/bn/...`), administrative management tools, and automated backup capabilities.

### Key Audit Highlights:
- **Baseline Test Suite:** 92 automated tests passing (322 assertions).
- **Core Tool Execution:** 100% client-side execution via HTML5 Canvas and browser JavaScript APIs, guaranteeing zero server uploads and high speed.
- **Identified Deficiencies:**
  1. *Branding Mismatch:* Development placeholder `OmniTools` persisted across environment files, config defaults, and language catalogs.
  2. *Origin Normalization:* Sitemaps and canonical tags defaulted to `http://localhost` in local runtime rather than explicitly utilizing the production origin `https://toolzeno.site`.
  3. *Robots.txt Sitemap Declaration:* Sitemap directive declared as a relative URI (`/sitemap.xml`) rather than an absolute production URL (`https://toolzeno.site/sitemap.xml`).
  4. *Title Tag Concatenation Duplication:* Layouts appended ` — config('app.name')` without checking if the page title already contained brand identifiers (`| ToolZeno`).
  5. *Sitemap Lastmod Tags:* XML sitemap lacked truthful `<lastmod>` change dates for tools and informational pages.
  6. *On-Page Content Depth:* Tool pages featured step-by-step instructions and limitations, but lacked comprehensive user FAQs and structured `BreadcrumbList` schema.
  7. *Contextual Internal Cross-Linking:* Related tool listings existed at the bottom of pages, but lacked inline contextual recommendations between workflows (e.g., Image Compressor to Image Resizer; Text Case Converter to Word Counter).

---

## 2. Complete URL Inventory

The table below catalogs every public endpoint, its search intent, status, language support, canonical URL, and recommended SEO action.

| Path | Page Type | Primary Search Intent | Language | HTTP Status | Indexability | Canonical Target | Recommended SEO Action | Priority |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| `/` | Redirect | Navigate to default language | - | 302 -> `/en` | Noindex (Redirect) | `https://toolzeno.site/en` | Maintain 302/301 root redirect to default `/en` | High |
| `/sitemap.xml` | XML Sitemap | Search engine crawl index | - | 200 XML | Non-HTML index | - | Generate absolute production URLs with reciprocal hreflang | Critical |
| `/robots.txt` | Crawler Directive | Bot crawl instruction | - | 200 Plain | Non-HTML | - | Disallow `/admin` and `/up`; declare absolute sitemap URL | Critical |
| `/en` | Home | Free online tools directory | en | 200 OK | Index, Follow | `https://toolzeno.site/en` | Brand as ToolZeno, optimize WebSite JSON-LD schema | Critical |
| `/bn` | Home | ফ্রি অনলাইন টুলস প্ল্যাটফর্ম | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn` | Self-referencing canonical, Bangla WebSite schema | Critical |
| `/en/tools` | Directory | Browse all online utilities | en | 200 OK | Index, Follow | `https://toolzeno.site/en/tools` | Semantic categories, search crawlability, breadcrumbs | High |
| `/bn/tools` | Directory | সকল অনলাইন টুলস তালিকা | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/tools` | Self-referencing canonical, localized category anchors | High |
| `/en/tools/category/text` | Category | Free text utilities | en | 200 OK | Index, Follow | `https://toolzeno.site/en/tools/category/text` | Category-specific meta title and description | Medium |
| `/bn/tools/category/text` | Category | টেক্সট টুলস তালিকা | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/tools/category/text` | Localized category descriptions | Medium |
| `/en/tools/category/calculators` | Category | Online calculator tools | en | 200 OK | Index, Follow | `https://toolzeno.site/en/tools/category/calculators` | Category-specific metadata | Medium |
| `/bn/tools/category/calculators` | Category | অনলাইন ক্যালকুলেটর | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/tools/category/calculators` | Localized category metadata | Medium |
| `/en/tools/category/developer` | Category | Online developer utilities | en | 200 OK | Index, Follow | `https://toolzeno.site/en/tools/category/developer` | Category-specific metadata | Medium |
| `/bn/tools/category/developer` | Category | ডেভেলপার টুলস | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/tools/category/developer` | Localized category metadata | Medium |
| `/en/tools/category/productivity`| Category | Productivity & QR tools | en | 200 OK | Index, Follow | `https://toolzeno.site/en/tools/category/productivity`| Category-specific metadata | Medium |
| `/bn/tools/category/productivity`| Category | প্রোডাক্টিভিটি টুলস | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/tools/category/productivity`| Localized category metadata | Medium |
| `/en/tools/category/image` | Category | Browser image tools | en | 200 OK | Index, Follow | `https://toolzeno.site/en/tools/category/image` | Category-specific metadata | Medium |
| `/bn/tools/category/image` | Category | ইমেজ টুলস | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/tools/category/image` | Localized category metadata | Medium |
| `/en/tools/category/pdf` | Category | Browser PDF utilities | en | 200 OK | Index, Follow | `https://toolzeno.site/en/tools/category/pdf` | Category-specific metadata | Medium |
| `/bn/tools/category/pdf` | Category | পিডিএফ টুলস | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/tools/category/pdf` | Localized category metadata | Medium |
| `/en/tools/word-counter` | Tool | Word and character counter | en | 200 OK | Index, Follow | `https://toolzeno.site/en/tools/word-counter` | Implement title pattern `Word Counter — Count Words and Characters \| ToolZeno`, FAQ, Breadcrumbs | Critical |
| `/bn/tools/word-counter` | Tool | বাংলা শব্দ ও অক্ষর গণনা | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/tools/word-counter` | Localized title, Bangla FAQ, reciprocal hreflang | Critical |
| `/en/tools/case-converter` | Tool | Text case converter online | en | 200 OK | Index, Follow | `https://toolzeno.site/en/tools/case-converter` | Implement title pattern `Text Case Converter — Change Text Case Online \| ToolZeno`, FAQ, Breadcrumbs | Critical |
| `/bn/tools/case-converter` | Tool | টেক্সট কেস কনভার্টার | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/tools/case-converter` | Localized title, explanation of unicameral Bangla | Critical |
| `/en/tools/percentage-calculator` | Tool | Percentage calculator online | en | 200 OK | Index, Follow | `https://toolzeno.site/en/tools/percentage-calculator` | Title pattern `Percentage Calculator — Calculate Percentages Online \| ToolZeno`, math formulas, FAQ | Critical |
| `/bn/tools/percentage-calculator` | Tool | শতকরা ও ছাড় ক্যালকুলেটর | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/tools/percentage-calculator` | Formula breakdown in Bangla, FAQ | Critical |
| `/en/tools/json-formatter` | Tool | JSON formatter and validator | en | 200 OK | Index, Follow | `https://toolzeno.site/en/tools/json-formatter` | Title pattern `JSON Formatter & Validator — Format JSON Online \| ToolZeno`, privacy info, FAQ | Critical |
| `/bn/tools/json-formatter` | Tool | JSON সাজানো ও যাচাই | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/tools/json-formatter` | Localized JSON developer guide | Critical |
| `/en/tools/qr-generator` | Tool | QR code generator online | en | 200 OK | Index, Follow | `https://toolzeno.site/en/tools/qr-generator` | Title pattern `QR Code Generator — Create QR Codes Online \| ToolZeno`, supported formats, FAQ | Critical |
| `/bn/tools/qr-generator` | Tool | কিউআর কোড তৈরি | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/tools/qr-generator` | Localized QR instructions and scanning notice | Critical |
| `/en/tools/image-compressor` | Tool | Compress images online | en | 200 OK | Index, Follow | `https://toolzeno.site/en/tools/image-compressor` | Title pattern `Image Compressor — Compress Images Online \| ToolZeno`, format specs, FAQ | Critical |
| `/bn/tools/image-compressor` | Tool | ছবির সাইজ কমানোর টুল | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/tools/image-compressor` | Localized quality guide and format notes | Critical |
| `/en/tools/image-resizer` | Tool | Resize image dimensions | en | 200 OK | Index, Follow | `https://toolzeno.site/en/tools/image-resizer` | Title pattern `Image Resizer — Resize Images Online \| ToolZeno`, aspect lock guide, FAQ | Critical |
| `/bn/tools/image-resizer` | Tool | ছবির মাপ পরিবর্তনের টুল | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/tools/image-resizer` | Localized aspect ratio instructions | Critical |
| `/en/tools/image-to-pdf` | Tool | Image to PDF converter | en | 200 OK | Index, Follow | `https://toolzeno.site/en/tools/image-to-pdf` | Title pattern `Image to PDF Converter — Convert Images to PDF \| ToolZeno`, page setup guide, FAQ | Critical |
| `/bn/tools/image-to-pdf` | Tool | ছবি থেকে পিডিএফ রূপান্তর | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/tools/image-to-pdf` | Localized PDF compilation guide | Critical |
| `/en/about` | Info | About ToolZeno platform | en | 200 OK | Index, Follow | `https://toolzeno.site/en/about` | Truthful mission statement, privacy ethics | High |
| `/bn/about` | Info | টুলজেনো সম্পর্কে জানুন | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/about` | Localized brand history and values | High |
| `/en/contact` | Info | Contact and feedback | en | 200 OK | Index, Follow | `https://toolzeno.site/en/contact` | Configurable support email, form validation | High |
| `/bn/contact` | Info | যোগাযোগ ও মতামত | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/contact` | Localized contact form | High |
| `/en/privacy-policy` | Legal | Privacy policy | en | 200 OK | Index, Follow | `https://toolzeno.site/en/privacy-policy` | Explicit client-side processing disclosure | High |
| `/bn/privacy-policy` | Legal | গোপনীয়তা নীতি | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/privacy-policy` | Localized privacy statement | High |
| `/en/terms-of-service` | Legal | Terms of service | en | 200 OK | Index, Follow | `https://toolzeno.site/en/terms-of-service` | Free tool usage terms, no warranty disclaimer | High |
| `/bn/terms-of-service` | Legal | ব্যবহারের শর্তাবলী | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/terms-of-service` | Localized terms of service | High |
| `/en/disclaimer` | Legal | Service disclaimer | en | 200 OK | Index, Follow | `https://toolzeno.site/en/disclaimer` | Mathematical & image processing accuracy disclaimer | Medium |
| `/bn/disclaimer` | Legal | ডিসক্লেইমার | bn | 200 OK | Index, Follow | `https://toolzeno.site/bn/disclaimer` | Localized disclaimer | Medium |

### Administrative & Private Routes (Properly Blocked from Indexing)
- `/admin/login`, `/admin/dashboard`, `/admin/tools`, `/admin/categories`, `/admin/content`, `/admin/ads`, `/admin/settings`, `/admin/media`, `/admin/audit-logs`, `/admin/diagnostics`, `/up` (Health check)
- **Status:** All admin/auth endpoints emit `X-Robots-Tag: noindex, nofollow, noarchive` via `SecurityHeadersMiddleware`.
- **Crawler Behavior:** Blocked in `robots.txt` (`Disallow: /admin`, `Disallow: /admin/`, `Disallow: /up`).
- **Sitemap Exclusion:** Zero admin or private URLs are included in the sitemap.

---

## 3. Prioritized Action Plan

### Critical Priority (Immediate Technical & Metadata Fixes)
1. **Rebranding:** Update all references of `OmniTools` to `ToolZeno` across `.env`, `.env.example`, `config/app.php`, language catalogs (`common.php`, `pages.php`, `tools.php`), and frontend scripts.
2. **Canonical Origin Normalization:** Implement centralized URL normalization ensuring production canonicals and sitemaps strictly resolve to `https://toolzeno.site` while remaining functional in local testing.
3. **Robots.txt Absolute Sitemap:** Update `public/robots.txt` to point to `Sitemap: https://toolzeno.site/sitemap.xml`.
4. **Title Tag Deduplication:** Upgrade metadata rendering in `layouts/app.blade.php` and `components/layouts/app.blade.php` so page titles ending in `| ToolZeno` do not suffer from double branding (`... | ToolZeno — ToolZeno`).
5. **Exact Tool Title Patterns:** Implement the 8 required title patterns matching search intent:
   - Word Counter: `Word Counter — Count Words and Characters | ToolZeno`
   - Text Case Converter: `Text Case Converter — Change Text Case Online | ToolZeno`
   - Percentage Calculator: `Percentage Calculator — Calculate Percentages Online | ToolZeno`
   - JSON Formatter: `JSON Formatter & Validator — Format JSON Online | ToolZeno`
   - QR Code Generator: `QR Code Generator — Create QR Codes Online | ToolZeno`
   - Image Compressor: `Image Compressor — Compress Images Online | ToolZeno`
   - Image Resizer: `Image Resizer — Resize Images Online | ToolZeno`
   - Image to PDF: `Image to PDF Converter — Convert Images to PDF | ToolZeno`

### High Priority (On-Page SEO & Structured Data Depth)
1. **Tool Page FAQs & Depth:** Add rich, actionable FAQ sections and realistic worked examples to each of the 8 tool views.
2. **BreadcrumbList Structured Data:** Add semantic JSON-LD `BreadcrumbList` markup to tool pages and informational pages.
3. **Contextual Cross-Linking:** Embed logical contextual links inside tool explanatory copy (e.g., Image Compressor referencing Image Resizer and Image to PDF).
4. **Sitemap Accurate Lastmod:** Populate `<lastmod>` timestamps in `SitemapController` using real timestamps.

### Medium Priority (Documentation & Operational Runbooks)
1. **Search Console & Bing Runbook:** Create step-by-step verification guide (`docs/SEARCH_CONSOLE_SETUP.md`).
2. **AdSense SEO Readiness Assessment:** Document site content suitability, navigation structure, and ad policy compliance (`docs/ADSENSE_SEO_READINESS.md`).
3. **Automated SEO Tests:** Expand test suite to assert exact title patterns, production canonical URLs, Breadcrumb schemas, and hreflang reciprocals.

### Low Priority (Continuous Monitoring & Scaling)
1. **Programmatic Tool Extensibility:** Maintain registry architecture so future tools automatically inherit SEO schema and sitemap eligibility without manual boilerplate.
2. **Core Web Vitals Telemetry:** Track real-world browser performance as traffic scales.
