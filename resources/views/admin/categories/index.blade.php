@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Category Taxonomies</h1>
            <p class="text-xs text-slate-400">
                Organize tools into logical groups with bilingual names and custom icons.
            </p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-3.5 py-2 text-xs font-bold text-white shadow-sm hover:bg-indigo-500 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Add New Category
        </a>
    </div>

    <!-- Table -->
    <div class="rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/60 text-slate-400 uppercase tracking-wider text-[10px] border-b border-slate-800">
                    <tr>
                        <th class="py-3 px-4">Order</th>
                        <th class="py-3 px-4">Category Name</th>
                        <th class="py-3 px-4">Slug</th>
                        <th class="py-3 px-4">Tools Count</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($categories as $category)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3.5 px-4 font-mono text-slate-400 text-xs">
                                #{{ $category->sort_order }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-white text-sm">{{ $category->name_en }}</div>
                                <div class="text-xs text-slate-400">{{ $category->name_bn }}</div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-indigo-400 text-xs">
                                {{ $category->slug }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-md bg-slate-800 px-2 py-0.5 text-xs font-semibold text-slate-300">
                                    {{ $category->tools_count }} tool(s)
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $category->is_active ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'bg-slate-800 text-slate-400' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $category->is_active ? 'bg-emerald-400' : 'bg-slate-500' }}"></span>
                                    {{ $category->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2 whitespace-nowrap">
                                <a 
                                    href="{{ route('admin.categories.edit', $category) }}" 
                                    class="rounded-lg bg-indigo-600 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500 transition-colors"
                                >
                                    Edit
                                </a>

                                @if($category->tools_count === 0)
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-rose-500/30 bg-rose-500/10 px-2.5 py-1.5 text-xs font-semibold text-rose-400 hover:bg-rose-500/20 transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="rounded-lg border border-slate-800 bg-slate-800/40 px-2.5 py-1.5 text-xs text-slate-600 cursor-not-allowed" title="Assigned tools prevent deletion">
                                        Locked (Has Tools)
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">
                                No categories created yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
