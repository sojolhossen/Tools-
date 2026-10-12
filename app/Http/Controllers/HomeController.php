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

        return view('pages.home', [
            'featuredTools' => $featuredTools,
            'popularTools' => $popularTools,
            'categories' => $categories,
            'allTools' => $allTools,
            'locale' => $locale,
        ]);
    }
}
