@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Tools Directory Management</h1>
            <p class="text-xs text-slate-400">
                Manage metadata, enable/disable tools, configure taxonomies and SEO titles.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-400 font-medium">All tool implementations are compiled in codebase</span>
        </div>
    </div>

    <!-- Filters & Tool Table -->
    <div class="rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden">
        <div class="p-4 border-b border-slate-800 bg-slate-900/60 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('admin.tools.index') }}" class="flex items-center gap-2">
                    <select name="category" onchange="this.form.submit()" class="rounded-xl border border-slate-700 bg-slate-950 px-3 py-1.5 text-xs text-slate-200 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name_en }} ({{ $cat->name_bn }})
                            </option>
                        @endforeach
                    </select>

                    <select name="status" onchange="this.form.submit()" class="rounded-xl border border-slate-700 bg-slate-950 px-3 py-1.5 text-xs text-slate-200 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </form>
            </div>
            <div class="text-xs text-slate-400">
                Showing <span class="font-bold text-white">{{ $tools->count() }}</span> tools
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/60 text-slate-400 uppercase tracking-wider text-[10px] border-b border-slate-800">
                    <tr>
                        <th class="py-3 px-4">Order</th>
                        <th class="py-3 px-4">Tool Name & Slug</th>
                        <th class="py-3 px-4">Category</th>
                        <th class="py-3 px-4">Flags</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($tools as $tool)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3.5 px-4 font-mono text-slate-400 text-xs">
                                #{{ $tool->sort_order }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-white text-sm">{{ $tool->name_en }}</div>
                                <div class="text-xs text-slate-400">{{ $tool->name_bn }}</div>
                                <div class="font-mono text-[11px] text-indigo-400 mt-0.5">/tools/{{ $tool->slug }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-md bg-slate-800 px-2 py-0.5 text-[11px] font-medium text-slate-300">
                                    {{ $tool->category?->name_en ?? $tool->category_slug }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 space-x-1">
                                @if($tool->is_featured)
                                    <span class="inline-flex items-center rounded-md bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 text-[10px] font-bold text-amber-400">Featured</span>
                                @endif
                                @if($tool->is_popular)
                                    <span class="inline-flex items-center rounded-md bg-purple-500/10 border border-purple-500/20 px-2 py-0.5 text-[10px] font-bold text-purple-400">Popular</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <form action="{{ route('admin.tools.toggle', $tool) }}" method="POST">
                                    @csrf
                                    <button 
                                        type="submit" 
                                        class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-semibold transition-colors {{ $tool->status === 'active' ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/25' : 'bg-slate-800 text-slate-400 hover:bg-slate-700' }}"
                                        title="Click to toggle status"
                                    >
                                        <span class="h-1.5 w-1.5 rounded-full {{ $tool->status === 'active' ? 'bg-emerald-400' : 'bg-slate-500' }}"></span>
                                        <span class="capitalize">{{ $tool->status }}</span>
                                    </button>
                                </form>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2 whitespace-nowrap">
                                <a 
                                    href="{{ route('tools.show', ['locale' => 'en', 'slug' => $tool->slug]) }}" 
                                    target="_blank"
                                    class="rounded-lg border border-slate-700 bg-slate-800 px-2.5 py-1.5 text-xs text-slate-300 hover:bg-slate-700 hover:text-white transition-colors"
                                >
                                    Preview
                                </a>
                                <a 
                                    href="{{ route('admin.tools.edit', $tool) }}" 
                                    class="rounded-lg bg-indigo-600 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500 transition-colors"
                                >
                                    Edit Metadata
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">
                                No tools matching the filter criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
