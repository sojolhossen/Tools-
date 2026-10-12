@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Content Management & Pages</h1>
            <p class="text-xs text-slate-400">
                Manage informational pages, legal policies, and tutorials in English and Bangla.
            </p>
        </div>
        <a href="{{ route('admin.content.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-3.5 py-2 text-xs font-bold text-white shadow-sm hover:bg-indigo-500 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Create New Page
        </a>
    </div>

    <!-- Table -->
    <div class="rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/60 text-slate-400 uppercase tracking-wider text-[10px] border-b border-slate-800">
                    <tr>
                        <th class="py-3 px-4">Page Title & Slug</th>
                        <th class="py-3 px-4">Bangla Title</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Published At</th>
                        <th class="py-3 px-4">Last Updated</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($pages as $page)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-white text-sm">{{ $page->title_en }}</div>
                                <div class="font-mono text-[11px] text-indigo-400 mt-0.5">/{{ $page->slug }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-300">
                                {{ $page->title_bn }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $page->status === 'published' ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'bg-amber-500/15 text-amber-400 border border-amber-500/30' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $page->status === 'published' ? 'bg-emerald-400' : 'bg-amber-400' }}"></span>
                                    <span class="capitalize">{{ $page->status }}</span>
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-400 font-mono text-[11px]">
                                {{ $page->published_at ? $page->published_at->format('Y-m-d') : 'Draft' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-400 font-mono text-[11px]">
                                {{ $page->updated_at->diffForHumans() }}
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2 whitespace-nowrap">
                                <a 
                                    href="{{ $page->getPublicUrl('en') }}" 
                                    target="_blank"
                                    class="rounded-lg border border-slate-700 bg-slate-800 px-2.5 py-1.5 text-xs text-slate-300 hover:bg-slate-700 hover:text-white transition-colors"
                                >
                                    Preview
                                </a>
                                <a 
                                    href="{{ route('admin.content.edit', $page) }}" 
                                    class="rounded-lg bg-indigo-600 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500 transition-colors"
                                >
                                    Edit
                                </a>

                                @if(!in_array($page->slug, ['about', 'contact', 'privacy-policy', 'terms-of-service', 'disclaimer'], true))
                                    <form action="{{ route('admin.content.destroy', $page) }}" method="POST" class="inline" onsubmit="return confirm('Delete this page permanently?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-rose-500/30 bg-rose-500/10 px-2.5 py-1.5 text-xs font-semibold text-rose-400 hover:bg-rose-500/20 transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">
                                No content pages found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
