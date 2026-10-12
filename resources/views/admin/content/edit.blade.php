@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Edit Page: {{ $page->title_en }}</h1>
            <p class="text-xs text-slate-400">
                Slug: <code class="font-mono text-indigo-400">/{{ $page->slug }}</code> | Last modified: {{ $page->updated_at->format('Y-m-d H:i') }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            @if(Route::has('pages.'.$page->slug))
                <a href="{{ route('pages.'.$page->slug, ['locale' => 'en']) }}" target="_blank" class="rounded-xl border border-slate-700 bg-slate-800 px-3 py-1.5 text-xs text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
                    View Live Page &rarr;
                </a>
            @endif
            <a href="{{ route('admin.content.index') }}" class="rounded-xl border border-slate-700 bg-slate-800 px-3 py-1.5 text-xs text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
                &larr; Back to Pages
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="rounded-xl bg-rose-500/10 border border-rose-500/20 p-4 text-xs text-rose-300">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.content.update', $page) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300">URL Slug</label>
                    <input 
                        type="text" 
                        name="slug" 
                        value="{{ old('slug', $page->slug) }}" 
                        required 
                        {{ in_array($page->slug, ['about', 'contact', 'privacy-policy', 'terms-of-service', 'disclaimer'], true) ? 'readonly' : '' }}
                        class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs font-mono text-indigo-300 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 {{ in_array($page->slug, ['about', 'contact', 'privacy-policy', 'terms-of-service', 'disclaimer'], true) ? 'opacity-60 cursor-not-allowed' : '' }}"
                    >
                    @if(in_array($page->slug, ['about', 'contact', 'privacy-policy', 'terms-of-service', 'disclaimer'], true))
                        <p class="mt-1 text-[11px] text-slate-500">Core system page slugs are locked to prevent broken route bindings.</p>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300">Publication State</label>
                    <select name="status" required class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="published" {{ old('status', $page->status) === 'published' ? 'selected' : '' }}>Published (Accessible to public)</option>
                        <option value="draft" {{ old('status', $page->status) === 'draft' ? 'selected' : '' }}>Draft (Private 404 for visitors)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300">Title (English)</label>
                    <input type="text" name="title_en" value="{{ old('title_en', $page->title_en) }}" required class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300">Title (Bangla)</label>
                    <input type="text" name="title_bn" value="{{ old('title_bn', $page->title_bn) }}" required class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300">Body Content Markdown (English)</label>
                <textarea name="body_en" rows="10" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 p-3.5 text-xs text-white font-mono focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('body_en', $page->body_en) }}</textarea>
                <p class="mt-1 text-[11px] text-slate-500">Markdown formatting (headings, bullet points, links, bold, code) rendered with XSS sanitization.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300">Body Content Markdown (Bangla)</label>
                <textarea name="body_bn" rows="10" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 p-3.5 text-xs text-white font-mono focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('body_bn', $page->body_bn) }}</textarea>
            </div>
        </div>

        <!-- SEO Metadata -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-400">SEO Metadata</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300">Meta Title (English)</label>
                    <input type="text" name="meta_title_en" value="{{ old('meta_title_en', $page->meta_title_en) }}" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300">Meta Title (Bangla)</label>
                    <input type="text" name="meta_title_bn" value="{{ old('meta_title_bn', $page->meta_title_bn) }}" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300">Meta Description (English)</label>
                <textarea name="meta_description_en" rows="2" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('meta_description_en', $page->meta_description_en) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300">Meta Description (Bangla)</label>
                <textarea name="meta_description_bn" rows="2" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('meta_description_bn', $page->meta_description_bn) }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.content.index') }}" class="rounded-xl border border-slate-700 bg-slate-800 px-4 py-2 text-xs text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
                Cancel
            </a>
            <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2 text-xs font-bold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                Save Page Changes
            </button>
        </div>
    </form>
</div>
@endsection
