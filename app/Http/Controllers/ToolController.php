<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\Tools\ToolRegistry;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class ToolController extends Controller
{
    public function __construct(
        private readonly ToolRegistry $toolRegistry,
    ) {}

    public function show(Request $request, string $locale, string $slug): View
    {
        $tool = $this->toolRegistry->find($slug);

        if ($tool === null || !$tool->isActive()) {
            abort(404);
        }

        if (!$tool->supportsLocale($locale)) {
            abort(404);
        }

        $relatedTools = $this->toolRegistry->getRelated($slug, 3);

        $prodOrigin = rtrim(config('app.url', 'https://toolzeno.site'), '/');
        $toolUrl = $prodOrigin . '/' . $locale . '/tools/' . $tool->slug;
        $homeUrl = $prodOrigin . '/' . $locale;
        $toolsIndexUrl = $prodOrigin . '/' . $locale . '/tools';
        $categoryUrl = $prodOrigin . '/' . $locale . '/tools/category/' . $tool->category;

        $schema = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'WebApplication',
                    'name' => $tool->getName($locale),
                    'description' => $tool->getDescription($locale),
                    'url' => $toolUrl,
                    'applicationCategory' => ucfirst($tool->category),
                    'operatingSystem' => 'All',
                    'browserRequirements' => 'Requires JavaScript. HTML5 Canvas and WebAssembly supported.',
                    'inLanguage' => ['en', 'bn'],
                    'offers' => [
                        '@type' => 'Offer',
                        'price' => '0',
                        'priceCurrency' => 'USD',
                    ],
                ],
                [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => __('common.nav.home', [], $locale),
                            'item' => $homeUrl,
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 2,
                            'name' => __('common.nav.tools', [], $locale),
                            'item' => $toolsIndexUrl,
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 3,
                            'name' => __('common.categories.' . $tool->category, [], $locale),
                            'item' => $categoryUrl,
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 4,
                            'name' => $tool->getName($locale),
                            'item' => $toolUrl,
                        ],
                    ],
                ],
            ],
        ];

        return view($tool->view, [
            'tool' => $tool,
            'relatedTools' => $relatedTools,
            'locale' => $locale,
            'schema' => $schema,
        ]);
    }
}
