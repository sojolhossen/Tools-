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
        return response()->json(['success' => false, 'message' => 'Please provide a valid URL to inspect.'], 400);
    }
    if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
        $url = 'https://' . $url;
    }

    $browserUA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36 (compatible; ToolZeno/1.0; +https://toolzeno.site)';

    $res = null;
    $error = null;

    // 1. Try HEAD request first
    try {
        $candidate = \Illuminate\Support\Facades\Http::timeout(8)
            ->withoutVerifying()
            ->withHeaders([
                'User-Agent' => $browserUA,
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ])
            ->withoutRedirecting()
            ->head($url);

        if ($candidate->status() !== 405) {
            $res = $candidate;
        }
    } catch (\Throwable $e) {
        $error = $e->getMessage();
    }

    // 2. If HEAD failed or gave 405 Method Not Allowed, fallback to lightweight GET request
    if (!$res) {
        try {
            $res = \Illuminate\Support\Facades\Http::timeout(8)
                ->withoutVerifying()
                ->withHeaders([
                    'User-Agent' => $browserUA,
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ])
                ->withoutRedirecting()
                ->get($url);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to connect to ' . $url . ': ' . ($e->getMessage() ?: ($error ?: 'Connection timed out')),
            ], 502);
        }
    }

    $headers = [];
    $rawText = '';
    $status = $res->status();
    $statusText = $res->reason() ?: 'OK';
    $server = null;
    $contentType = null;
    $location = null;

    $secHeadersList = [
        'strict-transport-security',
        'content-security-policy',
        'x-content-type-options',
        'x-frame-options',
        'referrer-policy',
        'permissions-policy',
        'x-xss-protection',
    ];

    foreach ($res->headers() as $name => $values) {
        $lower = strtolower($name);
        $val = implode(', ', (array) $values);
        $rawText .= $name . ': ' . $val . "\n";

        $category = 'general';
        if (in_array($lower, $secHeadersList)) $category = 'security';
        elseif (in_array($lower, ['cache-control', 'expires', 'pragma', 'etag', 'last-modified', 'age'])) $category = 'caching';
        elseif (in_array($lower, ['server', 'x-powered-by', 'alt-svc', 'via'])) $category = 'server';
        elseif (in_array($lower, ['set-cookie', 'cookie'])) $category = 'cookies';

        if ($lower === 'server') $server = $val;
        if ($lower === 'content-type') $contentType = $val;
        if ($lower === 'location') $location = $val;

        $headers[] = [
            'name' => $lower,
            'original_name' => $name,
            'val' => $val,
            'category' => $category,
        ];
    }

    // Sort: security headers first, then caching, then server, then others
    usort($headers, function ($a, $b) {
        $p = ['security' => 1, 'caching' => 2, 'server' => 3, 'cookies' => 4, 'general' => 5];
        $pa = $p[$a['category']] ?? 9;
        $pb = $p[$b['category']] ?? 9;
        if ($pa !== $pb) return $pa <=> $pb;
        return strcmp($a['name'], $b['name']);
    });

    return response()->json([
        'success' => true,
        'url' => $url,
        'status' => $status,
        'statusText' => $statusText,
        'server' => $server,
        'contentType' => $contentType,
        'location' => $location,
        'raw' => trim($rawText),
        'headers' => $headers,
    ]);
})->name('tools.api.http_headers');

// Internal DNS Propagation Checker proxy endpoint (immune to client-side adblockers and CORS failures)
Route::get('/api/tools/dns-propagation', function () {
    $domain = trim((string) request()->query('domain', request()->query('name', '')));
    $type = strtoupper(trim((string) request()->query('type', 'A')));
    $nodeId = trim((string) request()->query('node', ''));

    if ($domain === '') {
        return response()->json([
            'success' => false,
            'message' => 'Please provide a domain name.',
        ], 400);
    }

    $clean = preg_replace('#^https?://#i', '', $domain);
    $clean = trim(explode('/', $clean)[0]);
    $clean = trim(explode(':', $clean)[0]);
    $clean = strtolower($clean);

    if ($clean === '' || !str_contains($clean, '.')) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid domain format.',
        ], 422);
    }

    $allowedTypes = ['A', 'AAAA', 'MX', 'TXT', 'CNAME', 'NS'];
    if (!in_array($type, $allowedTypes)) {
        $type = 'A';
    }

    $allNodes = [
        'us-va' => ['id' => 'us-va', 'flag' => '🇺🇸', 'location' => 'USA (Virginia)', 'name' => 'Google Public DNS', 'url' => 'https://8.8.8.8/resolve'],
        'de-fra' => ['id' => 'de-fra', 'flag' => '🇩🇪', 'location' => 'Germany (Frankfurt)', 'name' => 'Cloudflare Edge (DE)', 'url' => 'https://1.1.1.1/dns-query'],
        'sg-sin' => ['id' => 'sg-sin', 'flag' => '🇸🇬', 'location' => 'Singapore (Asia East)', 'name' => 'Cloudflare AP Node', 'url' => 'https://1.0.0.1/dns-query'],
        'jp-tyo' => ['id' => 'jp-tyo', 'flag' => '🇯🇵', 'location' => 'Japan (Tokyo)', 'name' => 'AliDNS Asia Node', 'url' => 'https://dns.alidns.com/resolve'],
        'gb-lon' => ['id' => 'gb-lon', 'flag' => '🇬🇧', 'location' => 'United Kingdom (London)', 'name' => 'Cloudflare London', 'url' => 'https://cloudflare-dns.com/dns-query'],
        'au-syd' => ['id' => 'au-syd', 'flag' => '🇦🇺', 'location' => 'Australia (Sydney)', 'name' => 'Google Anycast (AU)', 'url' => 'https://8.8.4.4/resolve'],
        'us-ca' => ['id' => 'us-ca', 'flag' => '🇺🇸', 'location' => 'USA (California)', 'name' => 'Google Global Core', 'url' => 'https://dns.google/resolve'],
        'edge-isp' => ['id' => 'edge-isp', 'flag' => '🌐', 'location' => 'Authoritative ISP Edge', 'name' => 'System DNS Resolver', 'url' => 'native'],
    ];

    $checkNode = function ($node) use ($clean, $type) {
        $t0 = microtime(true);
        $data = '';
        $status = 'Failed';

        if ($node['url'] === 'native') {
            $typeConstMap = [
                'A' => DNS_A,
                'AAAA' => DNS_AAAA,
                'CNAME' => DNS_CNAME,
                'MX' => DNS_MX,
                'TXT' => DNS_TXT,
                'NS' => DNS_NS,
            ];
            $const = $typeConstMap[$type] ?? DNS_A;
            $recs = @dns_get_record($clean, $const);
            if (is_array($recs) && !empty($recs)) {
                $items = [];
                foreach ($recs as $r) {
                    if ($type === 'A' && !empty($r['ip'])) $items[] = $r['ip'];
                    elseif ($type === 'AAAA' && !empty($r['ipv6'])) $items[] = $r['ipv6'];
                    elseif ($type === 'CNAME' && !empty($r['target'])) $items[] = $r['target'];
                    elseif ($type === 'MX' && !empty($r['target'])) $items[] = ($r['pri'] ?? 10) . ' ' . $r['target'];
                    elseif ($type === 'TXT' && !empty($r['txt'])) $items[] = $r['txt'];
                    elseif ($type === 'NS' && !empty($r['target'])) $items[] = $r['target'];
                }
                $data = implode(', ', array_slice($items, 0, 3));
                $status = !empty($data) ? 'Resolved' : 'No Record';
            } else {
                $status = 'No Record';
                $data = 'No answers found';
            }
        } else {
            try {
                $res = \Illuminate\Support\Facades\Http::timeout(4)
                    ->withHeaders(['Accept' => 'application/dns-json', 'User-Agent' => 'ToolZeno-DNS/1.0'])
                    ->get($node['url'], ['name' => $clean, 'type' => $type]);
                if ($res->successful()) {
                    $ans = $res->json()['Answer'] ?? [];
                    if (!empty($ans) && is_array($ans)) {
                        $items = [];
                        foreach ($ans as $a) {
                            if (!empty($a['data'])) {
                                $items[] = (string) $a['data'];
                            }
                        }
                        $data = implode(', ', array_slice($items, 0, 3));
                        $status = !empty($data) ? 'Resolved' : 'No Record';
                    } else {
                        $status = 'No Record';
                        $data = 'No answers found';
                    }
                }
            } catch (\Throwable $e) {
                $status = 'Failed';
                $data = 'Timeout or unreachable';
            }
        }

        $latency = round((microtime(true) - $t0) * 1000);

        return [
            'id' => $node['id'],
            'flag' => $node['flag'],
            'location' => $node['location'],
            'name' => $node['name'],
            'status' => $status,
            'answer' => $data ?: 'No answers found',
            'latency' => $latency,
        ];
    };

    if ($nodeId !== '' && isset($allNodes[$nodeId])) {
        $singleResult = $checkNode($allNodes[$nodeId]);
        return response()->json([
            'success' => true,
            'node' => $singleResult,
        ]);
    }

    $results = [];
    foreach ($allNodes as $node) {
        $results[] = $checkNode($node);
    }

    return response()->json([
        'success' => true,
        'domain' => $clean,
        'type' => $type,
        'nodes' => $results,
    ]);
})->name('tools.api.dns_propagation');

// Internal SPF, DKIM & DMARC email security audit proxy endpoint (immune to CORS and client DoH blocking)
Route::get('/api/tools/spf-dkim-dmarc', function () {
    $domain = trim((string) request()->query('domain', request()->query('name', '')));
    $selector = trim((string) request()->query('selector', ''));

    if ($domain === '') {
        return response()->json([
            'success' => false,
            'message' => 'Please provide a domain name to audit.',
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

    // Helper to fetch DNS records server-side with fallback
    $lookupRecords = function (string $host, string $type = 'TXT'): array {
        $found = [];

        // 1. Google DoH
        try {
            $res = \Illuminate\Support\Facades\Http::timeout(5)
                ->get('https://dns.google/resolve', ['name' => $host, 'type' => $type]);
            if ($res->successful()) {
                $answers = $res->json()['Answer'] ?? [];
                if (is_array($answers)) {
                    foreach ($answers as $ans) {
                        if (!empty($ans['data'])) {
                            $found[] = trim((string) $ans['data'], '"');
                        }
                    }
                }
            }
        } catch (\Throwable $e) {}

        // 2. Cloudflare DoH fallback
        if (empty($found)) {
            try {
                $cfRes = \Illuminate\Support\Facades\Http::timeout(4)
                    ->withHeaders(['Accept' => 'application/dns-json'])
                    ->get('https://cloudflare-dns.com/dns-query', ['name' => $host, 'type' => $type]);
                if ($cfRes->successful()) {
                    $answers = $cfRes->json()['Answer'] ?? [];
                    if (is_array($answers)) {
                        foreach ($answers as $ans) {
                            if (!empty($ans['data'])) {
                                $found[] = trim((string) $ans['data'], '"');
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {}
        }

        // 3. PHP Native dns_get_record fallback
        if (empty($found)) {
            try {
                $const = $type === 'MX' ? DNS_MX : DNS_TXT;
                $native = @dns_get_record($host, $const);
                if (is_array($native)) {
                    foreach ($native as $n) {
                        if ($type === 'MX' && !empty($n['target'])) {
                            $found[] = ($n['pri'] ?? 10) . ' ' . $n['target'];
                        } elseif (!empty($n['txt'])) {
                            $found[] = $n['txt'];
                        }
                    }
                }
            } catch (\Throwable $e) {}
        }

        return $found;
    };

    // 1. SPF Inspection
    $apexTxts = $lookupRecords($clean, 'TXT');
    $spfRecords = [];
    foreach ($apexTxts as $t) {
        if (stripos($t, 'v=spf1') !== false) {
            $spfRecords[] = $t;
        }
    }

    $spfFound = !empty($spfRecords);
    $rawSpf = $spfFound ? $spfRecords[0] : null;
    $spfMultiple = count($spfRecords) > 1;

    $spfDetails = [
        'found' => $spfFound,
        'record' => $rawSpf,
        'multiple_records' => $spfMultiple,
        'qualifier' => null,
        'qualifier_label' => null,
        'includes' => [],
        'ips' => [],
        'status' => 'missing',
        'status_text' => 'No SPF record found',
    ];

    if ($spfFound && $rawSpf) {
        $tokens = preg_split('/\s+/', trim($rawSpf));
        foreach ($tokens as $tok) {
            if (preg_match('/^([+~?-])?all$/i', $tok, $qm)) {
                $q = $qm[1] ?? '+';
                $spfDetails['qualifier'] = $tok;
                $spfDetails['qualifier_label'] = match($q) {
                    '-' => 'HardFail (-all): Only specified senders authorized (Recommended)',
                    '~' => 'SoftFail (~all): Unauthorized mail accepted but tagged (Common)',
                    '?' => 'Neutral (?all): No policy stated',
                    '+' => 'Pass (+all): All servers authorized (Insecure)',
                    default => 'Default',
                };
            } elseif (stripos($tok, 'include:') === 0) {
                $spfDetails['includes'][] = substr($tok, 8);
            } elseif (stripos($tok, 'ip4:') === 0 || stripos($tok, 'ip6:') === 0) {
                $spfDetails['ips'][] = $tok;
            }
        }

        if ($spfMultiple) {
            $spfDetails['status'] = 'warning';
            $spfDetails['status_text'] = 'Multiple SPF records detected (RFC 7208 violation)';
        } elseif ($spfDetails['qualifier'] === '+all') {
            $spfDetails['status'] = 'warning';
            $spfDetails['status_text'] = 'Insecure policy (+all allows any sender)';
        } else {
            $spfDetails['status'] = 'valid';
            $spfDetails['status_text'] = 'Valid SPF Record';
        }
    }

    // 2. DMARC Inspection
    $dmarcTxts = $lookupRecords('_dmarc.' . $clean, 'TXT');
    $rawDmarc = null;
    foreach ($dmarcTxts as $t) {
        if (stripos($t, 'v=DMARC1') !== false) {
            $rawDmarc = $t;
            break;
        }
    }

    $dmarcFound = !empty($rawDmarc);
    $dmarcDetails = [
        'found' => $dmarcFound,
        'record' => $rawDmarc,
        'policy' => 'none',
        'policy_label' => 'No Policy',
        'subdomain_policy' => null,
        'percentage' => 100,
        'rua' => null,
        'ruf' => null,
        'status' => 'missing',
        'status_text' => 'No DMARC record found at _dmarc.' . $clean,
    ];

    if ($dmarcFound && $rawDmarc) {
        $parts = array_map('trim', explode(';', $rawDmarc));
        foreach ($parts as $p) {
            if (str_contains($p, '=')) {
                [$k, $v] = array_map('trim', explode('=', $p, 2));
                $kl = strtolower($k);
                if ($kl === 'p') {
                    $dmarcDetails['policy'] = strtolower($v);
                    $dmarcDetails['policy_label'] = match(strtolower($v)) {
                        'reject' => 'Reject (Highest security - drops fraudulent emails)',
                        'quarantine' => 'Quarantine (Directs suspicious emails to spam/junk)',
                        'none' => 'Monitoring (Reports only, no spoofing protection)',
                        default => $v,
                    };
                } elseif ($kl === 'sp') {
                    $dmarcDetails['subdomain_policy'] = strtolower($v);
                } elseif ($kl === 'pct') {
                    $dmarcDetails['percentage'] = (int) $v;
                } elseif ($kl === 'rua') {
                    $dmarcDetails['rua'] = $v;
                } elseif ($kl === 'ruf') {
                    $dmarcDetails['ruf'] = $v;
                }
            }
        }

        if ($dmarcDetails['policy'] === 'reject') {
            $dmarcDetails['status'] = 'protected';
            $dmarcDetails['status_text'] = 'Enforced (p=reject)';
        } elseif ($dmarcDetails['policy'] === 'quarantine') {
            $dmarcDetails['status'] = 'protected';
            $dmarcDetails['status_text'] = 'Enforced (p=quarantine)';
        } else {
            $dmarcDetails['status'] = 'monitoring';
            $dmarcDetails['status_text'] = 'Monitoring Only (p=none)';
        }
    }

    // 3. DKIM Inspection
    $testedSelector = $selector !== '' ? $selector : null;
    $rawDkim = null;
    $activeSelector = null;
    $discovered = false;

    if ($testedSelector) {
        $records = $lookupRecords($testedSelector . '._domainkey.' . $clean, 'TXT');
        foreach ($records as $r) {
            if (stripos($r, 'v=DKIM1') !== false || stripos($r, 'p=') !== false) {
                $rawDkim = $r;
                $activeSelector = $testedSelector;
                break;
            }
        }
    }

    // If specified selector not found or not provided, auto-discover common selectors
    if (!$rawDkim) {
        $candidates = ['20230601', '20210112', '20161025', 'google', 'default', 'k1', 's1', 'selector1', 'mail', 'smtp'];
        foreach ($candidates as $cand) {
            if ($testedSelector && $cand === $testedSelector) continue;
            $cRecs = $lookupRecords($cand . '._domainkey.' . $clean, 'TXT');
            foreach ($cRecs as $cr) {
                if (stripos($cr, 'v=DKIM1') !== false || stripos($cr, 'p=') !== false) {
                    $rawDkim = $cr;
                    $activeSelector = $cand;
                    $discovered = true;
                    break 2;
                }
            }
        }
    }

    $dkimFound = !empty($rawDkim);
    $dkimDetails = [
        'found' => $dkimFound,
        'record' => $rawDkim,
        'selector' => $activeSelector ?: ($testedSelector ?: 'default'),
        'specified_selector' => $testedSelector,
        'auto_discovered' => $discovered,
        'key_type' => 'rsa',
        'public_key' => null,
        'status' => $dkimFound ? 'valid' : 'not_found',
        'status_text' => $dkimFound
            ? ($discovered ? 'Discovered key with selector "' . $activeSelector . '"' : 'DKIM Key Verified')
            : 'No DKIM public key found for selector "' . ($testedSelector ?: 'default') . '"',
    ];

    if ($dkimFound && $rawDkim) {
        if (preg_match('/k=([^;\s]+)/i', $rawDkim, $km)) {
            $dkimDetails['key_type'] = $km[1];
        }
        if (preg_match('/p=([^;\s]+)/i', $rawDkim, $pm)) {
            $dkimDetails['public_key'] = $pm[1];
        }
    }

    // 4. MX Inspection
    $mxRaw = $lookupRecords($clean, 'MX');
    $mxList = [];
    foreach ($mxRaw as $mx) {
        $mxParts = preg_split('/\s+/', trim($mx));
        if (count($mxParts) >= 2) {
            $mxList[] = [
                'priority' => (int) $mxParts[0],
                'host' => rtrim($mxParts[1], '.'),
            ];
        } elseif (!empty($mx)) {
            $mxList[] = [
                'priority' => 10,
                'host' => rtrim($mx, '.'),
            ];
        }
    }
    usort($mxList, fn($a, $b) => $a['priority'] <=> $b['priority']);

    // 5. Score Calculation
    $score = 'C';
    $gradeText = 'Moderate Protection';
    $gradeColor = 'amber';

    if ($spfDetails['status'] === 'valid' && $dmarcDetails['status'] === 'protected' && $dkimDetails['found']) {
        $score = 'A+';
        $gradeText = 'Excellent Email Security — Highly Protected Against Spoofing & Phishing';
        $gradeColor = 'emerald';
    } elseif ($spfDetails['status'] === 'valid' && $dmarcDetails['status'] === 'protected') {
        $score = 'A';
        $gradeText = 'Strong Email Security — DMARC Enforced & SPF Valid';
        $gradeColor = 'emerald';
    } elseif ($spfDetails['status'] === 'valid' && $dmarcDetails['status'] === 'monitoring') {
        $score = 'B';
        $gradeText = 'Partially Protected — DMARC is only in monitoring mode (p=none)';
        $gradeColor = 'blue';
    } elseif (!$spfDetails['found'] && !$dmarcDetails['found']) {
        $score = 'F';
        $gradeText = 'Vulnerable — No SPF or DMARC records configured. Domain can be spoofed!';
        $gradeColor = 'rose';
    }

    return response()->json([
        'success' => true,
        'domain' => $clean,
        'score' => $score,
        'grade_text' => $gradeText,
        'grade_color' => $gradeColor,
        'spf' => $spfDetails,
        'dmarc' => $dmarcDetails,
        'dkim' => $dkimDetails,
        'mx' => $mxList,
    ]);
})->name('tools.api.email_security');

// Internal Speed Test fallback download streaming endpoint (high-throughput binary stream)
Route::get('/api/tools/speed-test/download', function () {
    $bytes = (int) request()->query('bytes', 5000000);
    if ($bytes <= 0) $bytes = 1000000;
    if ($bytes > 25000000) $bytes = 25000000;

    return response()->stream(function () use ($bytes) {
        $chunkSize = 65536;
        $chunk = str_repeat('0', $chunkSize);
        $sent = 0;
        while ($sent < $bytes) {
            $toSend = min($chunkSize, $bytes - $sent);
            if ($toSend < $chunkSize) {
                echo substr($chunk, 0, $toSend);
            } else {
                echo $chunk;
            }
            $sent += $toSend;
            if (ob_get_level() > 0) ob_flush();
            flush();
        }
    }, 200, [
        'Content-Type' => 'application/octet-stream',
        'Content-Length' => $bytes,
        'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        'Access-Control-Allow-Origin' => '*',
    ]);
})->name('tools.api.speed_test_download');

// Internal Speed Test fallback upload sink endpoint
Route::post('/api/tools/speed-test/upload', function () {
    $size = (int) request()->server('CONTENT_LENGTH', 0);
    return response()->json([
        'success' => true,
        'bytes' => $size,
    ], 200, [
        'Access-Control-Allow-Origin' => '*',
        'Cache-Control' => 'no-store',
    ]);
})->name('tools.api.speed_test_upload');

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
