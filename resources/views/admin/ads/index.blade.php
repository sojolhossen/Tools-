@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Advertisement Placement Management</h1>
            <p class="text-xs text-slate-400">
                Configure monetized ad slots, eligible positions, and provider slot identifiers safely.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-800 px-3 py-1 text-xs font-semibold text-slate-300">
                <span class="h-1.5 w-1.5 rounded-full {{ $placements->where('is_active', true)->count() > 0 ? 'bg-emerald-400' : 'bg-slate-500' }}"></span>
                {{ $placements->where('is_active', true)->count() }} Active Slots
            </span>
        </div>
    </div>

    <!-- Safety Notice -->
    <div class="rounded-2xl bg-amber-500/10 border border-amber-500/20 p-4 text-xs text-amber-300 flex items-start gap-3">
        <svg class="h-5 w-5 text-amber-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
        <div>
            <span class="font-bold">Monetization Safety Architecture:</span>
            <p class="mt-0.5 text-amber-200/80 leading-relaxed">
                Ad scripts are never loaded when placements are inactive. Raw HTML and script injection are architecturally disallowed to prevent security breaches and maintain 100% Core Web Vitals performance.
            </p>
        </div>
    </div>

    <!-- Placements Table -->
    <div class="rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/60 text-slate-400 uppercase tracking-wider text-[10px] border-b border-slate-800">
                    <tr>
                        <th class="py-3 px-4">Placement Name & Identifier</th>
                        <th class="py-3 px-4">Target Location</th>
                        <th class="py-3 px-4">Format</th>
                        <th class="py-3 px-4">Provider Slot ID</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($placements as $placement)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-white text-sm">{{ $placement->name }}</div>
                                <div class="font-mono text-[11px] text-indigo-400 mt-0.5">{{ $placement->identifier }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="rounded bg-slate-800 px-2 py-0.5 font-mono text-[11px] text-slate-300">
                                    {{ $placement->page_location }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 capitalize text-slate-300">
                                {{ $placement->format }}
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-400 text-xs">
                                {{ $placement->slot_id ?: '—' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <form action="{{ route('admin.ads.toggle', $placement) }}" method="POST">
                                    @csrf
                                    <button 
                                        type="submit" 
                                        class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-semibold transition-colors {{ $placement->is_active ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/25' : 'bg-slate-800 text-slate-400 hover:bg-slate-700' }}"
                                        title="Click to toggle status"
                                    >
                                        <span class="h-1.5 w-1.5 rounded-full {{ $placement->is_active ? 'bg-emerald-400' : 'bg-slate-500' }}"></span>
                                        <span>{{ $placement->is_active ? 'Active' : 'Disabled' }}</span>
                                    </button>
                                </form>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a 
                                    href="{{ route('admin.ads.edit', $placement) }}" 
                                    class="rounded-lg bg-indigo-600 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500 transition-colors"
                                >
                                    Configure
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">
                                No ad placements configured.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
