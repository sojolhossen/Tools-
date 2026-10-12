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

class ToolController extends Controller
{
    public function __construct(
        private readonly ToolRegistry $toolRegistry
    ) {}

    public function index(Request $request): View
    {
        $query = Tool::with('category')->orderBy('sort_order');

        if ($request->filled('category')) {
            $query->where('category_slug', $request->query('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $tools = $query->get();
        $categories = Category::orderBy('name_en')->get();

        return view('admin.tools.index', compact('tools', 'categories'));
    }

    public function edit(Tool $tool): View
    {
        $categories = Category::where('is_active', true)->orderBy('name_en')->get();
        $allowlistedViews = Tool::ALLOWLISTED_VIEWS;

        return view('admin.tools.edit', compact('tool', 'categories', 'allowlistedViews'));
    }

    public function update(Request $request, Tool $tool): RedirectResponse
    {
        $validated = $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_bn' => ['required', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'description_bn' => ['nullable', 'string'],
            'category_slug' => ['required', 'string', Rule::exists('categories', 'slug')],
            'icon' => ['required', 'string', 'max:100'],
            'view_handler' => ['required', 'string', Rule::in(Tool::ALLOWLISTED_VIEWS)],
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_featured' => ['nullable', 'boolean'],
            'is_popular' => ['nullable', 'boolean'],
            'meta_title_en' => ['nullable', 'string', 'max:255'],
            'meta_title_bn' => ['nullable', 'string', 'max:255'],
            'meta_description_en' => ['nullable', 'string'],
            'meta_description_bn' => ['nullable', 'string'],
            'tags' => ['nullable', 'string'],
        ]);

        $tagsArray = null;
        if (!empty($validated['tags'])) {
            $tagsArray = array_values(array_filter(array_map('trim', explode(',', $validated['tags']))));
        }

        $oldStatus = $tool->status;

        $tool->update([
            'name_en' => $validated['name_en'],
            'name_bn' => $validated['name_bn'],
            'description_en' => $validated['description_en'] ?? null,
            'description_bn' => $validated['description_bn'] ?? null,
            'category_slug' => $validated['category_slug'],
            'icon' => $validated['icon'],
            'view_handler' => $validated['view_handler'],
            'status' => $validated['status'],
            'sort_order' => (int) $validated['sort_order'],
            'is_featured' => $request->boolean('is_featured'),
            'is_popular' => $request->boolean('is_popular'),
            'meta_title_en' => $validated['meta_title_en'] ?? null,
            'meta_title_bn' => $validated['meta_title_bn'] ?? null,
            'meta_description_en' => $validated['meta_description_en'] ?? null,
            'meta_description_bn' => $validated['meta_description_bn'] ?? null,
            'tags' => $tagsArray,
        ]);

        $this->toolRegistry->refresh();

        AuditLogger::log(
            action: 'tool.updated',
            entityType: 'Tool',
            entityId: $tool->id,
            details: [
                'slug' => $tool->slug,
                'status' => $tool->status,
                'old_status' => $oldStatus,
                'category' => $tool->category_slug,
            ]
        );

        return redirect()->route('admin.tools.index')->with('success', "Tool [{$tool->name_en}] updated successfully.");
    }

    public function toggleStatus(Tool $tool): RedirectResponse
    {
        $newStatus = $tool->status === 'active' ? 'inactive' : 'active';

        // Security assertion: tool can only be active if its view template is allowlisted
        if ($newStatus === 'active' && !in_array($tool->view_handler, Tool::ALLOWLISTED_VIEWS, true)) {
            return back()->with('error', 'Cannot activate tool: Implementation is not in the verified allowlist.');
        }

        $tool->update(['status' => $newStatus]);
        $this->toolRegistry->refresh();

        AuditLogger::log(
            action: 'tool.status_toggled',
            entityType: 'Tool',
            entityId: $tool->id,
            details: ['slug' => $tool->slug, 'new_status' => $newStatus]
        );

        return back()->with('success', "Tool [{$tool->name_en}] is now {$newStatus}.");
    }
}
