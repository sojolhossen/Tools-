@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Edit Tool: {{ $tool->name_en }}</h1>
            <p class="text-xs text-slate-400">
                Slug: <code class="font-mono text-indigo-400">{{ $tool->slug }}</code> | Template: <code class="font-mono text-slate-300">{{ $tool->view_handler }}</code>
            </p>
        </div>
        <a href="{{ route('admin.tools.index') }}" class="rounded-xl border border-slate-700 bg-slate-800 px-3 py-1.5 text-xs text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
            &larr; Back to Directory
        </a>
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

    <form action="{{ route('admin.tools.update', $tool) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Primary Localization -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-400">Localized Display Names & Descriptions</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300">Name (English)</label>
                    <input type="text" name="name_en" value="{{ old('name_en', $tool->name_en) }}" required class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300">Name (Bangla)</label>
                    <input type="text" name="name_bn" value="{{ old('name_bn', $tool->name_bn) }}" required class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300">Short Description (English)</label>
                <textarea name="description_en" rows="2" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('description_en', $tool->description_en) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300">Short Description (Bangla)</label>
                <textarea name="description_bn" rows="2" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('description_bn', $tool->description_bn) }}</textarea>
            </div>
        </div>

        <!-- Taxonomy & Configuration -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-400">Classification & Execution Security</h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300">Category</label>
                    <select name="category_slug" required class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ old('category_slug', $tool->category_slug) === $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name_en }} ({{ $cat->name_bn }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300">Icon Identifier</label>
                    <input type="text" name="icon" value="{{ old('icon', $tool->icon) }}" required class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $tool->sort_order) }}" required class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300">Allowlisted View Handler (Reviewed Code Only)</label>
                    <select name="view_handler" required class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2 text-xs font-mono text-indigo-300 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        @foreach($allowlistedViews as $v)
                            <option value="{{ $v }}" {{ old('view_handler', $tool->view_handler) === $v ? 'selected' : '' }}>
                                {{ $v }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-[11px] text-slate-500">Arbitrary script injection is disabled by architecture.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300">Availability Status</label>
                    <select name="status" required class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="active" {{ old('status', $tool->status) === 'active' ? 'selected' : '' }}>Active (Publicly available)</option>
                        <option value="inactive" {{ old('status', $tool->status) === 'inactive' ? 'selected' : '' }}>Inactive (Unpublished)</option>
                    </select>
                </div>
            </div>

            <div class="pt-2 flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $tool->is_featured) ? 'checked' : '' }} class="rounded border-slate-700 bg-slate-950 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                    <span class="text-xs text-slate-300 font-medium">Featured Tool (Highlight on Homepage)</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_popular" value="1" {{ old('is_popular', $tool->is_popular) ? 'checked' : '' }} class="rounded border-slate-700 bg-slate-950 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                    <span class="text-xs text-slate-300 font-medium">Popular Utility Badge</span>
                </label>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300">Search Tags (Comma-separated)</label>
                <input type="text" name="tags" value="{{ old('tags', is_array($tool->tags) ? implode(', ', $tool->tags) : '') }}" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
        </div>

        <!-- SEO Metadata -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-400">Search Engine Optimization (SEO)</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300">Meta Title (English)</label>
                    <input type="text" name="meta_title_en" value="{{ old('meta_title_en', $tool->meta_title_en) }}" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300">Meta Title (Bangla)</label>
                    <input type="text" name="meta_title_bn" value="{{ old('meta_title_bn', $tool->meta_title_bn) }}" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300">Meta Description (English)</label>
                <textarea name="meta_description_en" rows="2" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('meta_description_en', $tool->meta_description_en) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300">Meta Description (Bangla)</label>
                <textarea name="meta_description_bn" rows="2" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('meta_description_bn', $tool->meta_description_bn) }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.tools.index') }}" class="rounded-xl border border-slate-700 bg-slate-800 px-4 py-2 text-xs text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
                Cancel
            </a>
            <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2 text-xs font-bold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                Save Tool Changes
            </button>
        </div>
    </form>
</div>
@endsection
