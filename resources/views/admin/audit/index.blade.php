@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Administrative Audit Trail</h1>
            <p class="text-xs text-slate-400">
                Immutable chronological log of all administrative actions, authentications, and configuration mutations.
            </p>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="rounded-2xl bg-slate-900 border border-slate-800 p-4">
        <form method="GET" action="{{ route('admin.audit.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input 
                    type="text" 
                    name="action" 
                    value="{{ request('action') }}" 
                    placeholder="Filter by action (e.g. tool.updated, admin.login)..." 
                    class="block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white placeholder:text-slate-500 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                >
            </div>

            <div class="flex-1 min-w-[200px]">
                <input 
                    type="text" 
                    name="actor" 
                    value="{{ request('actor') }}" 
                    placeholder="Filter by actor email..." 
                    class="block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white placeholder:text-slate-500 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                >
            </div>

            <button type="submit" class="rounded-xl bg-slate-800 border border-slate-700 px-4 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-700 hover:text-white transition-colors">
                Apply Filters
            </button>

            @if(request()->hasAny(['action', 'actor']))
                <a href="{{ route('admin.audit.index') }}" class="text-xs text-slate-400 hover:text-slate-300">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Logs Table -->
    <div class="rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/60 text-slate-400 uppercase tracking-wider text-[10px] border-b border-slate-800">
                    <tr>
                        <th class="py-3 px-4">Timestamp</th>
                        <th class="py-3 px-4">Actor</th>
                        <th class="py-3 px-4">Action</th>
                        <th class="py-3 px-4">Entity</th>
                        <th class="py-3 px-4">IP Address</th>
                        <th class="py-3 px-4">Change Summary</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3.5 px-4 font-mono text-slate-400 text-[11px] whitespace-nowrap">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="py-3.5 px-4 text-white font-medium">
                                {{ $log->user_email ?? 'CLI/System' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="rounded bg-slate-800 border border-slate-700/60 px-2 py-0.5 font-mono text-[11px] text-indigo-300">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-300">
                                {{ $log->entity_type ? "{$log->entity_type} #{$log->entity_id}" : '—' }}
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-400 text-[11px]">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-400 max-w-xs truncate" title="{{ json_encode($log->details) }}">
                                {{ $log->details ? json_encode($log->details) : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">
                                No audit events matching search criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
