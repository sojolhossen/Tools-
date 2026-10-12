<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\Tools\ToolRegistry;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class DirectoryController extends Controller
{
    public function __construct(
        private readonly ToolRegistry $toolRegistry,
    ) {}

    public function index(Request $request, string $locale = 'en'): View
    {
        $search = $request->query('q');
        $selectedCategory = $request->query('category');

        $tools = is_string($search) && trim($search) !== ''
            ? $this->toolRegistry->search($search)
            : $this->toolRegistry->active();

        if (is_string($selectedCategory) && $selectedCategory !== '' && $selectedCategory !== 'all') {
            $tools = $tools->filter(fn ($tool) => $tool->category === $selectedCategory);
        }

        return view('pages.directory', [
            'tools' => $tools,
            'categories' => $this->toolRegistry->getCategories(),
            'currentCategory' => $selectedCategory ?: 'all',
            'searchQuery' => $search ?: '',
            'locale' => $locale,
        ]);
    }

    public function category(Request $request, string $locale, string $category): View
    {
        $categories = $this->toolRegistry->getCategories();
        if (!array_key_exists($category, $categories)) {
            abort(404);
        }

        $tools = $this->toolRegistry->getByCategory($category);

        return view('pages.directory', [
            'tools' => $tools,
            'categories' => $categories,
            'currentCategory' => $category,
            'searchQuery' => '',
            'locale' => $locale,
        ]);
    }
}
