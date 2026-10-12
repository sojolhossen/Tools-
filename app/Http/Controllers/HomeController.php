<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\Tools\ToolRegistry;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class HomeController extends Controller
{
    public function __construct(
        private readonly ToolRegistry $toolRegistry,
    ) {}

    public function index(Request $request, string $locale = 'en'): View
    {
        $featuredTools = $this->toolRegistry->getFeatured();
        $popularTools = $this->toolRegistry->getPopular();
        $categories = $this->toolRegistry->getCategories();
        $allTools = $this->toolRegistry->active();

        $prodOrigin = rtrim(config('app.url', 'https://toolzeno.site'), '/');

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('app.name', 'ToolZeno'),
            'url' => $prodOrigin . '/' . $locale,
            'description' => __('common.meta_description_default'),
            'inLanguage' => ['en', 'bn'],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => $prodOrigin . '/' . $locale . '/tools?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];

        return view('pages.home', [
            'featuredTools' => $featuredTools,
            'popularTools' => $popularTools,
            'categories' => $categories,
            'allTools' => $allTools,
            'locale' => $locale,
            'schema' => $schema,
        ]);
    }
}
