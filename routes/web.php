<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdPlacementController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContentPageController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiagnosticController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ToolController as AdminToolController;
use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Global & Technical Routes
|--------------------------------------------------------------------------
*/

// Root URL redirects to canonical default English locale with Google Tag
Route::get('/', function () {
    $target = route('home', ['locale' => 'en']);
    $gaId = (string) (\App\Models\SiteSetting::get('analytics_measurement_id') ?: env('ANALYTICS_MEASUREMENT_ID', 'G-HJ4N0PWKP7'));
    $html = '<!DOCTYPE html><html><head><meta charset="UTF-8">' .
            '<script async src="https://www.googletagmanager.com/gtag/js?id=' . e($gaId) . '"></script>' .
            '<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag("js",new Date());gtag("config","' . e($gaId) . '");</script>' .
            '<meta http-equiv="refresh" content="0;url=' . e($target) . '">' .
            '<title>Redirecting...</title></head><body>Redirecting to <a href="' . e($target) . '">' . e($target) . '</a></body></html>';
    return response($html, 302, ['Location' => $target]);
})->name('root');

// XML Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// AdSense ads.txt crawler endpoint
Route::get('/ads.txt', function () {
    $adsPub = (string) (\App\Models\SiteSetting::get('adsense_publisher_id') ?: env('ADSENSE_PUBLISHER_ID', 'ca-pub-8336443496687004'));
    $pubId = str_replace('ca-', '', $adsPub);
    return response("google.com, {$pubId}, DIRECT, f08c47fec0942fa0\n", 200, [
        'Content-Type' => 'text/plain; charset=UTF-8',
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->name('ads.txt');

// Internal IP diagnostic endpoint (bypasses adblockers, CORS, and external rate limits)
Route::get('/api/tools/ip-info', function () {
    $rawIp = request()->header('CF-Connecting-IP') ?: (request()->header('X-Forwarded-For') ?: request()->ip());
    if ($rawIp && str_contains($rawIp, ',')) {
        $rawIp = trim(explode(',', $rawIp)[0]);
    }

    $isIpv4 = filter_var($rawIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ? true : false;
    $isIpv6 = filter_var($rawIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? true : false;

    $country = (string) (request()->header('CF-IPCountry') ?: '');
    $city = (string) (request()->header('CF-IPCity') ?: '');
    $asn = (string) (request()->header('CF-Connecting-ASN') ?: '');
    $isp = (string) (request()->header('CF-Connecting-Org') ?: '');
    $ray = (string) (request()->header('CF-Ray') ?: '');
    $colo = '';
    if ($ray && str_contains($ray, '-')) {
        $parts = explode('-', $ray);
        $colo = $parts[1] ?? '';
    }

    return response()->json([
        'ip' => $rawIp,
        'ipv4' => $isIpv4 ? $rawIp : '',
        'ipv6' => $isIpv6 ? $rawIp : '',
        'country' => $country ?: ($isIpv4 || $isIpv6 ? 'Detected' : 'Unknown'),
        'city' => $city ?: ($colo ? 'Cloudflare Edge (' . strtoupper($colo) . ')' : 'Global Network'),
        'asn' => $asn ? 'AS' . $asn : 'AS Network',
        'isp' => $isp ?: 'Public ISP Network',
        'timestamp' => now()->toIso8601String(),
    ]);
})->name('tools.api.ip_info');

// Internal IP Geolocation Lookup proxy endpoint (immune to adblockers, CORS, ISP blocks)
Route::get('/api/tools/ip-lookup', function () {
    $query = trim((string) request()->query('query', ''));

    if ($query === '') {
        $rawIp = request()->header('CF-Connecting-IP') ?: (request()->header('X-Forwarded-For') ?: request()->ip());
        if ($rawIp && str_contains($rawIp, ',')) {
            $rawIp = trim(explode(',', $rawIp)[0]);
        }
        $query = (string) $rawIp;
    }

    $clean = preg_replace('#^https?://#i', '', $query);
    $clean = trim(explode('/', $clean)[0]);
    $clean = trim(explode(':', $clean)[0]);

    if ($clean === '' || $clean === '127.0.0.1' || $clean === '::1' || $clean === 'localhost') {
        $clean = '8.8.8.8';
    }

    // 1. Primary: Server-side query to ipwho.is
    try {
        $res = \Illuminate\Support\Facades\Http::timeout(5)->get('https://ipwho.is/' . urlencode($clean));
        if ($res->successful()) {
            $data = $res->json();
            if (is_array($data) && ($data['success'] ?? false) !== false) {
                return response()->json($data);
            }
        }
    } catch (\Throwable $e) {}

    // 2. Secondary: Fallback to freeipapi.com
    try {
        $res2 = \Illuminate\Support\Facades\Http::timeout(5)->get('https://freeipapi.com/api/json/' . urlencode($clean));
        if ($res2->successful()) {
            $d = $res2->json();
            if (is_array($d)) {
                return response()->json([
                    'success' => true,
                    'ip' => $d['ipAddress'] ?? $clean,
                    'type' => $d['ipVersion'] ?? 'IPv4',
                    'country' => $d['countryName'] ?? '',
                    'country_code' => $d['countryCode'] ?? '',
                    'region' => $d['regionName'] ?? '',
                    'city' => $d['cityName'] ?? '',
                    'postal' => $d['zipCode'] ?? '',
                    'latitude' => $d['latitude'] ?? null,
                    'longitude' => $d['longitude'] ?? null,
                    'timezone' => $d['timeZone'] ?? '',
                    'connection' => [
                        'isp' => $d['isp'] ?? '',
                        'asn' => '',
                    ],
                ]);
            }
        }
    } catch (\Throwable $e) {}

    // 3. Tertiary: Fallback to ip-api.com
    try {
        $res3 = \Illuminate\Support\Facades\Http::timeout(5)->get('http://ip-api.com/json/' . urlencode($clean));
        if ($res3->successful()) {
            $d3 = $res3->json();
            if (is_array($d3) && ($d3['status'] ?? '') === 'success') {
                return response()->json([
                    'success' => true,
                    'ip' => $d3['query'] ?? $clean,
                    'country' => $d3['country'] ?? '',
                    'country_code' => $d3['countryCode'] ?? '',
                    'region' => $d3['regionName'] ?? '',
                    'city' => $d3['city'] ?? '',
                    'postal' => $d3['zip'] ?? '',
                    'latitude' => $d3['lat'] ?? null,
                    'longitude' => $d3['lon'] ?? null,
                    'timezone' => $d3['timezone'] ?? '',
                    'connection' => [
                        'isp' => $d3['isp'] ?? ($d3['org'] ?? ''),
                        'asn' => $d3['as'] ?? '',
                    ],
                ]);
            }
        }
    } catch (\Throwable $e) {}

    return response()->json([
        'success' => false,
        'message' => 'Unable to locate IP geolocation details for ' . $clean . '. Please check the address.',
    ], 404);
})->name('tools.api.ip_lookup');

// Internal WHOIS / RDAP lookup proxy endpoint (immune to CORS, browser blockers, and registry redirect limitations)
Route::get('/api/tools/whois', function () {
    $domain = trim((string) request()->query('domain', request()->query('query', '')));

    if ($domain === '') {
        return response()->json([
            'success' => false,
            'message' => 'Please provide a domain name to query.',
        ], 400);
    }

    $clean = preg_replace('#^https?://#i', '', $domain);
    $clean = trim(explode('/', $clean)[0]);
    $clean = trim(explode(':', $clean)[0]);
    $clean = strtolower($clean);

    if ($clean === '' || !str_contains($clean, '.')) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid domain format. Example: example.com',
        ], 422);
    }

    $created = '';
    $expires = '';
    $updated = '';
    $registrar = '';
    $nameservers = [];
    $status = [];
    $raw = null;

    // 1. Primary: Query authoritative RDAP bootstrap service
    try {
        $res = \Illuminate\Support\Facades\Http::timeout(7)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (compatible; ToolZenoBot/1.0; +https://toolzeno.site)',
                'Accept' => 'application/rdap+json, application/json',
            ])
            ->get('https://rdap.org/domain/' . urlencode($clean));

        if ($res->successful()) {
            $raw = $res->json();
            if (is_array($raw)) {
                if (!empty($raw['events']) && is_array($raw['events'])) {
                    foreach ($raw['events'] as $e) {
                        $action = $e['eventAction'] ?? '';
                        $date = $e['eventDate'] ?? '';
                        if ($action === 'registration') $created = $date;
                        if ($action === 'expiration') $expires = $date;
                        if ($action === 'last changed') $updated = $date;
                    }
                }

                if (!empty($raw['entities']) && is_array($raw['entities'])) {
                    foreach ($raw['entities'] as $ent) {
                        $roles = $ent['roles'] ?? [];
                        if (!empty($ent['vcardArray'][1]) && is_array($ent['vcardArray'][1])) {
                            foreach ($ent['vcardArray'][1] as $v) {
                                if (isset($v[0]) && $v[0] === 'fn' && !empty($v[3])) {
                                    if (in_array('registrar', $roles) || empty($registrar)) {
                                        $registrar = (string) $v[3];
                                    }
                                }
                            }
                        }
                    }
                }

                if (!empty($raw['nameservers']) && is_array($raw['nameservers'])) {
                    foreach ($raw['nameservers'] as $ns) {
                        if (!empty($ns['ldhName'])) {
                            $nameservers[] = strtolower($ns['ldhName']);
                        }
                    }
                }

                if (!empty($raw['status']) && is_array($raw['status'])) {
                    $status = $raw['status'];
                }
            }
        }
    } catch (\Throwable $e) {}

    // 2. Secondary fallback: who-dat if registrar or events missing
    if (empty($registrar) || empty($created)) {
        try {
            $fallbackRes = \Illuminate\Support\Facades\Http::timeout(5)
                ->get('https://who-dat.as93.net/' . urlencode($clean));
            if ($fallbackRes->successful()) {
                $fJson = $fallbackRes->json();
                if (is_array($fJson)) {
                    if (empty($created) && !empty($fJson['created'])) $created = $fJson['created'];
                    if (empty($expires) && !empty($fJson['expires'])) $expires = $fJson['expires'];
                    if (empty($registrar) && !empty($fJson['registrar']['name'])) $registrar = $fJson['registrar']['name'];
                    if (empty($nameservers) && !empty($fJson['nameservers']) && is_array($fJson['nameservers'])) {
                        foreach ($fJson['nameservers'] as $ns) {
                            if (is_array($ns) && !empty($ns['name'])) {
                                $nameservers[] = strtolower($ns['name']);
                            } elseif (is_string($ns)) {
                                $nameservers[] = strtolower($ns);
                            }
                        }
                    }
                    if (empty($raw)) {
                        $raw = $fJson;
                    }
                }
            }
        } catch (\Throwable $e) {}
    }

    // 3. Fallback nameservers from authoritative DNS NS records if still empty
    if (empty($nameservers)) {
        try {
            $dns = @dns_get_record($clean, DNS_NS);
            if (is_array($dns)) {
                foreach ($dns as $record) {
                    if (!empty($record['target'])) {
                        $nameservers[] = strtolower($record['target']);
                    }
                }
            }
        } catch (\Throwable $e) {}
    }

    $nameservers = array_values(array_unique($nameservers));

    if (empty($raw) && empty($nameservers)) {
        return response()->json([
            'success' => false,
            'message' => 'No WHOIS / RDAP records found for ' . $clean . '. The domain might be available or restricted.',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'domain' => $clean,
        'created' => $created,
        'expires' => $expires,
        'updated' => $updated,
        'registrar' => $registrar ?: 'Protected / Registry Privacy',
        'nameservers' => $nameservers,
        'status' => $status,
        'raw' => $raw,
    ]);
})->name('tools.api.whois');

// Internal DNS Record Lookup proxy endpoint (immune to browser CORS, client adblockers, and ISP DoH filtering)
Route::get('/api/tools/dns-lookup', function () {
    $domain = trim((string) request()->query('name', request()->query('domain', '')));
    $type = strtoupper(trim((string) request()->query('type', 'A')));

    if ($domain === '') {
        return response()->json([
            'success' => false,
            'message' => 'Please provide a domain name to lookup.',
        ], 400);
    }

    $clean = preg_replace('#^https?://#i', '', $domain);
    $clean = trim(explode('/', $clean)[0]);
    $clean = trim(explode(':', $clean)[0]);
    $clean = strtolower($clean);

    if ($clean === '' || !str_contains($clean, '.')) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid domain format. Example: example.com',
        ], 422);
    }

    $allowedTypes = ['A', 'AAAA', 'CNAME', 'MX', 'TXT', 'NS', 'SOA', 'CAA', 'PTR', 'SRV'];
    if (!in_array($type, $allowedTypes)) {
        $type = 'A';
    }

    $records = [];
    $status = 0;

    // 1. Primary: Google DoH API
    try {
        $res = \Illuminate\Support\Facades\Http::timeout(6)
            ->get('https://dns.google/resolve', [
                'name' => $clean,
                'type' => $type,
            ]);

        if ($res->successful()) {
            $data = $res->json();
            $status = $data['Status'] ?? 0;
            if (!empty($data['Answer']) && is_array($data['Answer'])) {
                foreach ($data['Answer'] as $ans) {
                    $records[] = [
                        'name' => rtrim($ans['name'] ?? $clean, '.'),
                        'type' => $type,
                        'TTL' => $ans['TTL'] ?? 300,
                        'data' => (string) ($ans['data'] ?? ''),
                    ];
                }
            }
        }
    } catch (\Throwable $e) {}

    // 2. Secondary fallback: Cloudflare DoH API
    if (empty($records) && $status !== 3) {
        try {
            $cfRes = \Illuminate\Support\Facades\Http::timeout(5)
                ->withHeaders([
                    'Accept' => 'application/dns-json',
                    'User-Agent' => 'ToolZeno-DNS/1.0',
                ])
                ->get('https://cloudflare-dns.com/dns-query', [
                    'name' => $clean,
                    'type' => $type,
                ]);

            if ($cfRes->successful()) {
                $cfData = $cfRes->json();
                $status = $cfData['Status'] ?? $status;
                if (!empty($cfData['Answer']) && is_array($cfData['Answer'])) {
                    foreach ($cfData['Answer'] as $ans) {
                        $records[] = [
                            'name' => rtrim($ans['name'] ?? $clean, '.'),
                            'type' => $type,
                            'TTL' => $ans['TTL'] ?? 300,
                            'data' => (string) ($ans['data'] ?? ''),
                        ];
                    }
                }
            }
        } catch (\Throwable $e) {}
    }

    // 3. Tertiary fallback: PHP Native dns_get_record()
    if (empty($records) && $status !== 3) {
        try {
            $typeConstMap = [
                'A' => DNS_A,
                'AAAA' => DNS_AAAA,
                'CNAME' => DNS_CNAME,
                'MX' => DNS_MX,
                'TXT' => DNS_TXT,
                'NS' => DNS_NS,
                'SOA' => DNS_SOA,
                'CAA' => defined('DNS_CAA') ? DNS_CAA : DNS_ANY,
            ];

            if (isset($typeConstMap[$type])) {
                $nativeRecords = @dns_get_record($clean, $typeConstMap[$type]);
                if (is_array($nativeRecords)) {
                    foreach ($nativeRecords as $rec) {
                        $val = '';
                        if ($type === 'A' && !empty($rec['ip'])) $val = $rec['ip'];
                        elseif ($type === 'AAAA' && !empty($rec['ipv6'])) $val = $rec['ipv6'];
                        elseif ($type === 'CNAME' && !empty($rec['target'])) $val = $rec['target'];
                        elseif ($type === 'MX' && !empty($rec['target'])) $val = ($rec['pri'] ?? 10) . ' ' . $rec['target'];
                        elseif ($type === 'TXT' && !empty($rec['txt'])) $val = $rec['txt'];
                        elseif ($type === 'NS' && !empty($rec['target'])) $val = $rec['target'];
                        elseif ($type === 'SOA') $val = ($rec['mname'] ?? '') . ' ' . ($rec['rname'] ?? '');
                        elseif ($type === 'CAA') $val = ($rec['flags'] ?? 0) . ' ' . ($rec['tag'] ?? '') . ' "' . ($rec['value'] ?? '') . '"';

                        if ($val !== '') {
                            $records[] = [
                                'name' => $rec['host'] ?? $clean,
                                'type' => $type,
                                'TTL' => $rec['ttl'] ?? 300,
                                'data' => $val,
                            ];
                        }
                    }
                }
            }
        } catch (\Throwable $e) {}
    }

    if ($status === 3) {
        return response()->json([
            'success' => false,
            'status' => 3,
            'message' => 'NXDOMAIN: Domain ' . $clean . ' does not exist or has no active DNS zone.',
            'records' => [],
        ], 404);
    }

    return response()->json([
        'success' => true,
        'domain' => $clean,
        'type' => $type,
        'status' => $status,
        'records' => $records,
    ]);
})->name('tools.api.dns_lookup');

// Internal Reverse DNS (rDNS / PTR) lookup endpoint (immune to CORS and browser DoH blocking)
Route::get('/api/tools/rdns-lookup', function () {
    $rawIp = trim((string) request()->query('ip', request()->query('query', '')));

    if ($rawIp === '') {
        return response()->json([
            'success' => false,
            'message' => 'Please provide an IP address to lookup.',
        ], 400);
    }

    $cleanIp = preg_replace('#^https?://#i', '', $rawIp);
    $cleanIp = trim(explode('/', $cleanIp)[0]);
    $cleanIp = trim(explode(':', $cleanIp)[0]);

    if (!filter_var($cleanIp, FILTER_VALIDATE_IP)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid IP address format. Please enter a valid IPv4 or IPv6 address.',
        ], 422);
    }

    $isIpv4 = filter_var($cleanIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    $arpa = '';
    $hostname = '';
    $ttl = 300;

    if ($isIpv4) {
        $parts = explode('.', $cleanIp);
        $arpa = implode('.', array_reverse($parts)) . '.in-addr.arpa';

        // 1. Primary: Native gethostbyaddr
        try {
            $host = @gethostbyaddr($cleanIp);
            if ($host && $host !== $cleanIp) {
                $hostname = $host;
            }
        } catch (\Throwable $e) {}
    } else {
        // IPv6 reverse arpa format
        $addr = inet_pton($cleanIp);
        if ($addr !== false) {
            $unpack = unpack('H*hex', $addr);
            $hex = $unpack['hex'] ?? '';
            $arpa = implode('.', array_reverse(str_split($hex))) . '.ip6.arpa';
        }
    }

    // 2. Secondary: Query DoH via Google
    if (empty($hostname) && $arpa !== '') {
        try {
            $res = \Illuminate\Support\Facades\Http::timeout(5)
                ->get('https://dns.google/resolve', [
                    'name' => $arpa,
                    'type' => 'PTR',
                ]);
            if ($res->successful()) {
                $answers = $res->json()['Answer'] ?? [];
                if (!empty($answers) && is_array($answers)) {
                    $hostname = rtrim($answers[0]['data'] ?? '', '.');
                    $ttl = $answers[0]['TTL'] ?? $ttl;
                }
            }
        } catch (\Throwable $e) {}
    }

    // 3. Tertiary: Cloudflare DoH
    if (empty($hostname) && $arpa !== '') {
        try {
            $cfRes = \Illuminate\Support\Facades\Http::timeout(5)
                ->withHeaders(['Accept' => 'application/dns-json'])
                ->get('https://cloudflare-dns.com/dns-query', [
                    'name' => $arpa,
                    'type' => 'PTR',
                ]);
            if ($cfRes->successful()) {
                $answers = $cfRes->json()['Answer'] ?? [];
                if (!empty($answers) && is_array($answers)) {
                    $hostname = rtrim($answers[0]['data'] ?? '', '.');
                    $ttl = $answers[0]['TTL'] ?? $ttl;
                }
            }
        } catch (\Throwable $e) {}
    }

    if (empty($hostname)) {
        return response()->json([
            'success' => false,
            'ip' => $cleanIp,
            'arpa' => $arpa,
            'message' => 'No PTR (reverse DNS) record found for ' . $cleanIp . '.',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'ip' => $cleanIp,
        'hostname' => $hostname,
        'arpa' => $arpa,
        'ttl' => $ttl,
    ]);
})->name('tools.api.rdns_lookup');

// Internal HTTP Headers Inspection proxy endpoint (immune to CORS)
Route::get('/api/tools/http-headers', function () {
    $url = trim((string) request()->query('url', ''));
    if ($url === '') {
        return response()->json(['success' => false, 'message' => 'URL is required.'], 400);
    }
    if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
        $url = 'https://' . $url;
    }
    try {
        $res = \Illuminate\Support\Facades\Http::timeout(7)
            ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; ToolZeno-Inspector/1.0)'])
            ->withoutRedirecting()
            ->head($url);

        $headers = [];
        foreach ($res->headers() as $name => $values) {
            $headers[] = [
                'name' => strtolower($name),
                'val' => implode(', ', (array) $values),
            ];
        }

        return response()->json([
            'success' => true,
            'url' => $url,
            'status' => $res->status(),
            'statusText' => $res->reason() ?: 'OK',
            'headers' => $headers,
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Unable to connect to ' . $url . ': ' . $e->getMessage(),
        ], 502);
    }
})->name('tools.api.http_headers');

/*
|--------------------------------------------------------------------------
| Localized Public Routes (en, bn)
|--------------------------------------------------------------------------
*/
Route::prefix('{locale}')
    ->where(['locale' => 'en|bn'])
    ->middleware(['setLocale'])
    ->group(function () {

        // Homepage
        Route::get('/', [HomeController::class, 'index'])->name('home');

        // Tools Directory & Categories
        Route::get('/tools', [DirectoryController::class, 'index'])->name('tools.index');
        Route::get('/tools/category/{category}', [DirectoryController::class, 'category'])->name('tools.category');

        // Individual Tool Routes
        Route::get('/tools/{slug}', [ToolController::class, 'show'])->name('tools.show');

        // Informational Pages
        Route::get('/about', [PageController::class, 'about'])->name('pages.about');
        Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
        Route::post('/contact', [PageController::class, 'submitContact'])->name('pages.contact.submit');
        Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('pages.privacy');
        Route::get('/terms-of-service', [PageController::class, 'terms'])->name('pages.terms');
        Route::get('/disclaimer', [PageController::class, 'disclaimer'])->name('pages.disclaimer');

        // Dynamic CMS / Guide Pages
        Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');
    });

/*
|--------------------------------------------------------------------------
| Protected Admin Authentication & Management Routes (Phase 2)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    // Unauthenticated Guest Login
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::get('/auth/login', fn () => redirect()->route('admin.login'))->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Protected Admin Area (Requires authenticated administrator)
    Route::middleware(['admin'])->group(function () {
        Route::get('/', fn () => redirect()->route('admin.dashboard'));
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Tool Management
        Route::get('/tools', [AdminToolController::class, 'index'])->name('admin.tools.index');
        Route::get('/tools/{tool}/edit', [AdminToolController::class, 'edit'])->name('admin.tools.edit');
        Route::put('/tools/{tool}', [AdminToolController::class, 'update'])->name('admin.tools.update');
        Route::post('/tools/{tool}/toggle', [AdminToolController::class, 'toggleStatus'])->name('admin.tools.toggle');

        // Category Management
        Route::resource('categories', CategoryController::class, ['as' => 'admin'])->except(['show']);

        // Content Pages Management (CMS)
        Route::resource('content', ContentPageController::class, [
            'as' => 'admin',
            'parameters' => ['content' => 'page'],
        ])->except(['show']);

        // Advertisement Placements
        Route::get('/ads', [AdPlacementController::class, 'index'])->name('admin.ads.index');
        Route::get('/ads/{placement}/edit', [AdPlacementController::class, 'edit'])->name('admin.ads.edit');
        Route::put('/ads/{placement}', [AdPlacementController::class, 'update'])->name('admin.ads.update');
        Route::post('/ads/{placement}/toggle', [AdPlacementController::class, 'toggle'])->name('admin.ads.toggle');

        // Site Settings & SEO
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');

        // Media Library
        Route::get('/media', [MediaController::class, 'index'])->name('admin.media.index');
        Route::post('/media', [MediaController::class, 'store'])->name('admin.media.store');
        Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('admin.media.destroy');

        // Audience Analytics
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics.index');

        // Audit Trail
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('admin.audit.index');

        // Diagnostics
        Route::get('/diagnostics', [DiagnosticController::class, 'index'])->name('admin.diagnostics.index');
    });
});
