<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentPage;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContentPageController extends Controller
{
    private const CORE_SLUGS = ['about', 'contact', 'privacy-policy', 'terms-of-service', 'disclaimer'];

    public function index(): View
    {
        $pages = ContentPage::orderBy('title_en')->get();
        return view('admin.content.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.content.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'slug' => ['required', 'string', 'max:100', 'alpha_dash', 'unique:content_pages,slug'],
            'title_en' => ['required', 'string', 'max:255'],
            'title_bn' => ['required', 'string', 'max:255'],
            'body_en' => ['nullable', 'string'],
            'body_bn' => ['nullable', 'string'],
            'meta_title_en' => ['nullable', 'string', 'max:255'],
            'meta_title_bn' => ['nullable', 'string', 'max:255'],
            'meta_description_en' => ['nullable', 'string'],
            'meta_description_bn' => ['nullable', 'string'],
            'status' => ['required', 'string', Rule::in(['published', 'draft'])],
        ]);

        $page = ContentPage::create([
            'slug' => $validated['slug'],
            'title_en' => $validated['title_en'],
            'title_bn' => $validated['title_bn'],
            'body_en' => $validated['body_en'] ?? null,
            'body_bn' => $validated['body_bn'] ?? null,
            'meta_title_en' => $validated['meta_title_en'] ?? null,
            'meta_title_bn' => $validated['meta_title_bn'] ?? null,
            'meta_description_en' => $validated['meta_description_en'] ?? null,
            'meta_description_bn' => $validated['meta_description_bn'] ?? null,
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        AuditLogger::log(
            action: 'page.created',
            entityType: 'ContentPage',
            entityId: $page->id,
            details: ['slug' => $page->slug, 'status' => $page->status]
        );

        return redirect()->route('admin.content.index')->with('success', "Page [{$page->title_en}] created successfully.");
    }

    public function edit(ContentPage $page): View
    {
        return view('admin.content.edit', compact('page'));
    }

    public function update(Request $request, ContentPage $page): RedirectResponse
    {
        $validated = $request->validate([
            'slug' => ['required', 'string', 'max:100', 'alpha_dash', Rule::unique('content_pages', 'slug')->ignore($page->id)],
            'title_en' => ['required', 'string', 'max:255'],
            'title_bn' => ['required', 'string', 'max:255'],
            'body_en' => ['nullable', 'string'],
            'body_bn' => ['nullable', 'string'],
            'meta_title_en' => ['nullable', 'string', 'max:255'],
            'meta_title_bn' => ['nullable', 'string', 'max:255'],
            'meta_description_en' => ['nullable', 'string'],
            'meta_description_bn' => ['nullable', 'string'],
            'status' => ['required', 'string', Rule::in(['published', 'draft'])],
        ]);

        $publishedAt = $page->published_at;
        if ($validated['status'] === 'published' && $page->status === 'draft') {
            $publishedAt = now();
        }

        $page->update([
            'slug' => $validated['slug'],
            'title_en' => $validated['title_en'],
            'title_bn' => $validated['title_bn'],
            'body_en' => $validated['body_en'] ?? null,
            'body_bn' => $validated['body_bn'] ?? null,
            'meta_title_en' => $validated['meta_title_en'] ?? null,
            'meta_title_bn' => $validated['meta_title_bn'] ?? null,
            'meta_description_en' => $validated['meta_description_en'] ?? null,
            'meta_description_bn' => $validated['meta_description_bn'] ?? null,
            'status' => $validated['status'],
            'published_at' => $publishedAt,
        ]);

        AuditLogger::log(
            action: 'page.updated',
            entityType: 'ContentPage',
            entityId: $page->id,
            details: ['slug' => $page->slug, 'status' => $page->status]
        );

        return redirect()->route('admin.content.index')->with('success', "Page [{$page->title_en}] updated successfully.");
    }

    public function destroy(ContentPage $page): RedirectResponse
    {
        if (in_array($page->slug, self::CORE_SLUGS, true)) {
            return back()->with('error', "Safety Guard: Cannot delete core system page [{$page->slug}]. You may set its status to draft or edit content instead.");
        }

        $title = $page->title_en;
        $page->delete();

        AuditLogger::log(
            action: 'page.deleted',
            entityType: 'ContentPage',
            details: ['title' => $title]
        );

        return redirect()->route('admin.content.index')->with('success', "Page [{$title}] deleted successfully.");
    }
}
