<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ContentPage;
use App\Models\SiteSetting;
use App\Models\Tool;
use App\Support\Tools\ToolRegistry;
use Illuminate\Http\Response;

final class SitemapController extends Controller
{
    public function __construct(
        private readonly ToolRegistry $toolRegistry,
    ) {}

    public function index(): Response
    {
        if (! (bool) SiteSetting::get('sitemap_enabled', true)) {
            abort(404);
        }

        $prodOrigin = rtrim(config('app.url', 'https://toolzeno.site'), '/');
        $normalize = fn (string $url): string => (string) preg_replace('#^https?://[^/]+#', $prodOrigin, $url);

        $locales = ['en', 'bn'];
        $categories = array_keys($this->toolRegistry->getCategories());
        $tools = $this->toolRegistry->active();

        // Index DB tool records for accurate lastmod timestamps
        $dbTools = collect();
        try {
            $dbTools = Tool::all()->keyBy('slug');
        } catch (\Throwable $e) {
            // Fallback if DB table not available
        }

        // Compute latest tool timestamp for homepage/directory lastmod
        $latestToolTimestamp = 0;

        $urls = [];

        // 1. Tools (Only active tools)
        foreach ($tools as $tool) {
            $toolLastmod = null;
            /** @var Tool|null $dbTool */
            $dbTool = $dbTools->get($tool->slug);
            if ($dbTool && $dbTool->updated_at) {
                $toolLastmod = $dbTool->updated_at->toIso8601String();
                $latestToolTimestamp = max($latestToolTimestamp, $dbTool->updated_at->timestamp);
            } else {
                $viewPath = resource_path('views/' . str_replace('.', '/', $tool->view) . '.blade.php');
                if (file_exists($viewPath)) {
                    $mtime = filemtime($viewPath);
                    $toolLastmod = date('c', $mtime);
                    $latestToolTimestamp = max($latestToolTimestamp, $mtime);
                }
            }

            foreach ($locales as $loc) {
                if ($tool->supportsLocale($loc)) {
                    $urls[] = [
                        'loc' => $normalize(route('tools.show', ['locale' => $loc, 'slug' => $tool->slug])),
                        'lastmod' => $toolLastmod,
                        'changefreq' => 'weekly',
                        'priority' => '0.9',
                        'alternates' => [
                            'en' => $normalize(route('tools.show', ['locale' => 'en', 'slug' => $tool->slug])),
                            'bn' => $normalize(route('tools.show', ['locale' => 'bn', 'slug' => $tool->slug])),
                        ],
                    ];
                }
            }
        }

        $systemLastmod = $latestToolTimestamp > 0 ? date('c', $latestToolTimestamp) : now()->toIso8601String();

        // 2. Homepage (English & Bangla)
        $urls[] = [
            'loc' => $normalize(url('/en')),
            'lastmod' => $systemLastmod,
            'changefreq' => 'daily',
            'priority' => '1.0',
            'alternates' => [
                'en' => $normalize(url('/en')),
                'bn' => $normalize(url('/bn')),
            ],
        ];
        $urls[] = [
            'loc' => $normalize(url('/bn')),
            'lastmod' => $systemLastmod,
            'changefreq' => 'daily',
            'priority' => '0.9',
            'alternates' => [
                'en' => $normalize(url('/en')),
                'bn' => $normalize(url('/bn')),
            ],
        ];

        // 3. Directory
        foreach ($locales as $loc) {
            $urls[] = [
                'loc' => $normalize(route('tools.index', ['locale' => $loc])),
                'lastmod' => $systemLastmod,
                'changefreq' => 'daily',
                'priority' => '0.9',
                'alternates' => [
                    'en' => $normalize(route('tools.index', ['locale' => 'en'])),
                    'bn' => $normalize(route('tools.index', ['locale' => 'bn'])),
                ],
            ];
        }

        // 4. Categories
        foreach ($categories as $cat) {
            foreach ($locales as $loc) {
                $urls[] = [
                    'loc' => $normalize(route('tools.category', ['locale' => $loc, 'category' => $cat])),
                    'lastmod' => $systemLastmod,
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                    'alternates' => [
                        'en' => $normalize(route('tools.category', ['locale' => 'en', 'category' => $cat])),
                        'bn' => $normalize(route('tools.category', ['locale' => 'bn', 'category' => $cat])),
                    ],
                ];
            }
        }

        // 5. Core Informational Pages (About, Contact, Privacy, Terms, Disclaimer)
        $coreInfoPages = [
            'about' => ['route' => 'pages.about', 'priority' => '0.7', 'view' => 'pages/about.blade.php'],
            'contact' => ['route' => 'pages.contact', 'priority' => '0.7', 'view' => 'pages/contact.blade.php'],
            'privacy-policy' => ['route' => 'pages.privacy', 'priority' => '0.5', 'view' => 'pages/privacy.blade.php'],
            'terms-of-service' => ['route' => 'pages.terms', 'priority' => '0.5', 'view' => 'pages/terms.blade.php'],
            'disclaimer' => ['route' => 'pages.disclaimer', 'priority' => '0.5', 'view' => 'pages/disclaimer.blade.php'],
        ];

        foreach ($coreInfoPages as $slug => $info) {
            $infoLastmod = null;
            $viewPath = resource_path('views/' . $info['view']);
            if (file_exists($viewPath)) {
                $infoLastmod = date('c', filemtime($viewPath));
            }

            foreach ($locales as $loc) {
                $urls[] = [
                    'loc' => $normalize(route($info['route'], ['locale' => $loc])),
                    'lastmod' => $infoLastmod,
                    'changefreq' => 'monthly',
                    'priority' => $info['priority'],
                    'alternates' => [
                        'en' => $normalize(route($info['route'], ['locale' => 'en'])),
                        'bn' => $normalize(route($info['route'], ['locale' => 'bn'])),
                    ],
                ];
            }
        }

        // 6. Dynamic Published CMS Content Pages (excluding core pages to avoid duplicates)
        try {
            $publishedPages = ContentPage::where('status', 'published')
                ->whereNotIn('slug', array_keys($coreInfoPages))
                ->get();

            foreach ($publishedPages as $page) {
                $pageLastmod = ($page->updated_at ?? $page->published_at ?? now())->toIso8601String();
                $pageUrlEn = $normalize($page->getPublicUrl('en'));
                $pageUrlBn = $normalize($page->getPublicUrl('bn'));

                foreach ($locales as $loc) {
                    $urls[] = [
                        'loc' => $loc === 'en' ? $pageUrlEn : $pageUrlBn,
                        'lastmod' => $pageLastmod,
                        'changefreq' => 'monthly',
                        'priority' => '0.6',
                        'alternates' => [
                            'en' => $pageUrlEn,
                            'bn' => $pageUrlBn,
                        ],
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Fallback if DB not queried
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

        foreach ($urls as $item) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($item['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
            if (!empty($item['lastmod'])) {
                $xml .= "    <lastmod>" . htmlspecialchars($item['lastmod'], ENT_XML1, 'UTF-8') . "</lastmod>\n";
            }
            $xml .= "    <changefreq>{$item['changefreq']}</changefreq>\n";
            $xml .= "    <priority>{$item['priority']}</priority>\n";
            foreach ($item['alternates'] as $altLocale => $altUrl) {
                $xml .= '    <xhtml:link rel="alternate" hreflang="' . $altLocale . '" href="' . htmlspecialchars($altUrl, ENT_XML1, 'UTF-8') . '" />' . "\n";
            }
            $xml .= '    <xhtml:link rel="alternate" hreflang="x-default" href="' . htmlspecialchars($item['alternates']['en'], ENT_XML1, 'UTF-8') . '" />' . "\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
