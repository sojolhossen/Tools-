<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tool;
use App\Services\AuditLogger;
use App\Support\Tools\ToolRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        private readonly ToolRegistry $toolRegistry
    ) {}

    public function index(): View
    {
        $categories = Category::withCount('tools')->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'slug' => ['required', 'string', 'max:100', 'alpha_dash', 'unique:categories,slug'],
            'name_en' => ['required', 'string', 'max:255'],
            'name_bn' => ['required', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'description_bn' => ['nullable', 'string'],
            'icon' => ['required', 'string', 'max:100'],
            'color' => ['required', 'string', 'max:50'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
            'meta_title_en' => ['nullable', 'string', 'max:255'],
            'meta_title_bn' => ['nullable', 'string', 'max:255'],
            'meta_description_en' => ['nullable', 'string'],
            'meta_description_bn' => ['nullable', 'string'],
        ]);

        $category = Category::create([
            'slug' => $validated['slug'],
            'name_en' => $validated['name_en'],
            'name_bn' => $validated['name_bn'],
            'description_en' => $validated['description_en'] ?? null,
            'description_bn' => $validated['description_bn'] ?? null,
            'icon' => $validated['icon'],
            'color' => $validated['color'],
            'sort_order' => (int) $validated['sort_order'],
            'is_active' => $request->boolean('is_active', true),
            'meta_title_en' => $validated['meta_title_en'] ?? null,
            'meta_title_bn' => $validated['meta_title_bn'] ?? null,
            'meta_description_en' => $validated['meta_description_en'] ?? null,
            'meta_description_bn' => $validated['meta_description_bn'] ?? null,
        ]);

        $this->toolRegistry->refresh();

        AuditLogger::log(
            action: 'category.created',
            entityType: 'Category',
            entityId: $category->id,
            details: ['slug' => $category->slug, 'name_en' => $category->name_en]
        );

        return redirect()->route('admin.categories.index')->with('success', "Category [{$category->name_en}] created successfully.");
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'slug' => ['required', 'string', 'max:100', 'alpha_dash', Rule::unique('categories', 'slug')->ignore($category->id)],
            'name_en' => ['required', 'string', 'max:255'],
            'name_bn' => ['required', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'description_bn' => ['nullable', 'string'],
            'icon' => ['required', 'string', 'max:100'],
            'color' => ['required', 'string', 'max:50'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
            'meta_title_en' => ['nullable', 'string', 'max:255'],
            'meta_title_bn' => ['nullable', 'string', 'max:255'],
            'meta_description_en' => ['nullable', 'string'],
            'meta_description_bn' => ['nullable', 'string'],
        ]);

        $oldSlug = $category->slug;

        $category->update([
            'slug' => $validated['slug'],
            'name_en' => $validated['name_en'],
            'name_bn' => $validated['name_bn'],
            'description_en' => $validated['description_en'] ?? null,
            'description_bn' => $validated['description_bn'] ?? null,
            'icon' => $validated['icon'],
            'color' => $validated['color'],
            'sort_order' => (int) $validated['sort_order'],
            'is_active' => $request->boolean('is_active'),
            'meta_title_en' => $validated['meta_title_en'] ?? null,
            'meta_title_bn' => $validated['meta_title_bn'] ?? null,
            'meta_description_en' => $validated['meta_description_en'] ?? null,
            'meta_description_bn' => $validated['meta_description_bn'] ?? null,
        ]);

        // If slug changed, update dependent tools to prevent broken links
        if ($oldSlug !== $category->slug) {
            Tool::where('category_slug', $oldSlug)->update(['category_slug' => $category->slug]);
        }

        $this->toolRegistry->refresh();

        AuditLogger::log(
            action: 'category.updated',
            entityType: 'Category',
            entityId: $category->id,
            details: ['slug' => $category->slug, 'old_slug' => $oldSlug]
        );

        return redirect()->route('admin.categories.index')->with('success', "Category [{$category->name_en}] updated successfully.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        $toolCount = Tool::where('category_slug', $category->slug)->count();
        if ($toolCount > 0) {
            return back()->with('error', "Safety Guard: Cannot delete category [{$category->name_en}] because {$toolCount} tool(s) are assigned to it. Reassign tools first.");
        }

        $categoryName = $category->name_en;
        $category->delete();
        $this->toolRegistry->refresh();

        AuditLogger::log(
            action: 'category.deleted',
            entityType: 'Category',
            details: ['name_en' => $categoryName]
        );

        return redirect()->route('admin.categories.index')->with('success', "Category [{$categoryName}] was deleted.");
    }
}
