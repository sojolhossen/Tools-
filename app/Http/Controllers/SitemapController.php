<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\Tools\ToolRegistry;
use Illuminate\Http\Response;

final class SitemapController extends Controller
{
    public function __construct(
        private readonly ToolRegistry $toolRegistry,
    ) {}

    public function index(): Response
    {
        $locales = ['en', 'bn'];
        $categories = array_keys($this->toolRegistry->getCategories());
        $tools = $this->toolRegistry->active();
        $pages = ['pages.about', 'pages.contact', 'pages.privacy', 'pages.terms', 'pages.disclaimer'];

        $urls = [];

        // Homepage
        $urls[] = [
            'loc' => url('/en'),
            'changefreq' => 'daily',
            'priority' => '1.0',
            'alternates' => [
                'en' => url('/en'),
                'bn' => url('/bn'),
            ],
        ];
        $urls[] = [
            'loc' => url('/bn'),
            'changefreq' => 'daily',
            'priority' => '0.9',
            'alternates' => [
                'en' => url('/en'),
                'bn' => url('/bn'),
            ],
        ];

        // Directory
        foreach ($locales as $loc) {
            $urls[] = [
                'loc' => route('tools.index', ['locale' => $loc]),
                'changefreq' => 'daily',
                'priority' => '0.9',
                'alternates' => [
                    'en' => route('tools.index', ['locale' => 'en']),
                    'bn' => route('tools.index', ['locale' => 'bn']),
                ],
            ];
        }

        // Categories
        foreach ($categories as $cat) {
            foreach ($locales as $loc) {
                $urls[] = [
                    'loc' => route('tools.category', ['locale' => $loc, 'category' => $cat]),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                    'alternates' => [
                        'en' => route('tools.category', ['locale' => 'en', 'category' => $cat]),
                        'bn' => route('tools.category', ['locale' => 'bn', 'category' => $cat]),
                    ],
                ];
            }
        }

        // Tools
        foreach ($tools as $tool) {
            foreach ($locales as $loc) {
                if ($tool->supportsLocale($loc)) {
                    $urls[] = [
                        'loc' => route('tools.show', ['locale' => $loc, 'slug' => $tool->slug]),
                        'changefreq' => 'weekly',
                        'priority' => '0.9',
                        'alternates' => [
                            'en' => route('tools.show', ['locale' => 'en', 'slug' => $tool->slug]),
                            'bn' => route('tools.show', ['locale' => 'bn', 'slug' => $tool->slug]),
                        ],
                    ];
                }
            }
        }

        // Informational Pages
        foreach ($pages as $routeName) {
            foreach ($locales as $loc) {
                $urls[] = [
                    'loc' => route($routeName, ['locale' => $loc]),
                    'changefreq' => 'monthly',
                    'priority' => '0.5',
                    'alternates' => [
                        'en' => route($routeName, ['locale' => 'en']),
                        'bn' => route($routeName, ['locale' => 'bn']),
                    ],
                ];
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

        foreach ($urls as $item) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($item['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
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
