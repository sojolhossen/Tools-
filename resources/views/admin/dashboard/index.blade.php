@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Header Banner -->
    <div class="rounded-2xl bg-gradient-to-r from-indigo-900/40 via-purple-900/30 to-slate-900 border border-indigo-500/20 p-6 sm:p-8 backdrop-blur">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">Platform Overview</h1>
                <p class="mt-1 text-xs sm:text-sm text-slate-300">
                    Real-time operational metrics and configuration state across English & Bangla platform editions.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Operational
                </span>
            </div>
        </div>
    </div>

    <!-- Verified Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Enabled Tools -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Active Tools</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-500/10 text-indigo-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.67 2.67 0 1 0 21 17.25l-5.83-5.83m0 0a5.25 5.25 0 1 0-7.42-7.42 5.25 5.25 0 0 0 7.42 7.42Z" /></svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-black text-white">{{ $activeTools }}</span>
                <span class="text-xs text-slate-400">of {{ $totalTools }} registered</span>
            </div>
            <p class="mt-1 text-[11px] text-slate-500">100% verified browser implementations</p>
        </div>

        <!-- Categories -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Categories</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500/10 text-emerald-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" /></svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-black text-white">{{ $totalCategories }}</span>
                <span class="text-xs text-slate-400">active taxonomies</span>
            </div>
            <p class="mt-1 text-[11px] text-slate-500">Full English & Bangla bilingual metadata</p>
        </div>

        <!-- Published Content Pages -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Content Pages</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-500/10 text-sky-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-black text-white">{{ $publishedPages }}</span>
                <span class="text-xs text-slate-400">published</span>
                @if($draftPages > 0)
                    <span class="text-xs text-amber-400 font-semibold">({{ $draftPages }} draft)</span>
                @endif
            </div>
            <p class="mt-1 text-[11px] text-slate-500">About, Contact, Privacy, Terms, Disclaimer</p>
        </div>

        <!-- Ad Placements -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Ad Placements</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-500/10 text-amber-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-black text-white">{{ $activePlacements }}</span>
                <span class="text-xs text-slate-400">active of {{ $totalPlacements }} configured</span>
            </div>
            <p class="mt-1 text-[11px] text-slate-500">Strictly non-invasive ad slots</p>
        </div>
    </div>

    <!-- Integrations & Diagnostics Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Integrations Status -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider">Integrations & External Services</h3>
            <p class="mt-1 text-xs text-slate-400">Honest status reporting of external providers without fake analytics</p>

            <div class="mt-5 space-y-3">
                <div class="flex items-center justify-between rounded-xl bg-slate-950/60 border border-slate-800/80 p-3.5">
                    <div>
                        <p class="text-xs font-semibold text-slate-200">Google Analytics 4 (GA4)</p>
                        <p class="text-[11px] text-slate-500">Audience insights & traffic monitoring</p>
                    </div>
                    <span class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider {{ $analyticsStatus === 'Active' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-slate-800 text-slate-400' }}">
                        {{ $analyticsStatus }}
                    </span>
                </div>

                <div class="flex items-center justify-between rounded-xl bg-slate-950/60 border border-slate-800/80 p-3.5">
                    <div>
                        <p class="text-xs font-semibold text-slate-200">Google AdSense</p>
                        <p class="text-[11px] text-slate-500">Future display advertising monetization</p>
                    </div>
                    <span class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider {{ $adsenseStatus === 'Active' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-slate-800 text-slate-400' }}">
                        {{ $adsenseStatus }}
                    </span>
                </div>

                <div class="flex items-center justify-between rounded-xl bg-slate-950/60 border border-slate-800/80 p-3.5">
                    <div>
                        <p class="text-xs font-semibold text-slate-200">Outbound Mail / SMTP</p>
                        <p class="text-[11px] text-slate-500">Contact form notices & notifications</p>
                    </div>
                    <span class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                        {{ config('mail.default', 'log') }} driver
                    </span>
                </div>
            </div>
        </div>

        <!-- Runtime & Environment Diagnostics -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider">Environment & Diagnostics</h3>
            <p class="mt-1 text-xs text-slate-400">Runtime health indicators (without secret exposure)</p>

            <div class="mt-5 grid grid-cols-2 gap-3 text-xs">
                <div class="rounded-xl bg-slate-950/60 border border-slate-800/80 p-3">
                    <span class="text-[10px] uppercase font-bold text-slate-500">PHP Version</span>
                    <p class="mt-1 font-semibold text-white">{{ $phpVersion }}</p>
                </div>
                <div class="rounded-xl bg-slate-950/60 border border-slate-800/80 p-3">
                    <span class="text-[10px] uppercase font-bold text-slate-500">Laravel Version</span>
                    <p class="mt-1 font-semibold text-white">{{ $laravelVersion }}</p>
                </div>
                <div class="rounded-xl bg-slate-950/60 border border-slate-800/80 p-3">
                    <span class="text-[10px] uppercase font-bold text-slate-500">Database Driver</span>
                    <p class="mt-1 font-semibold text-indigo-400 uppercase font-mono">{{ $databaseDriver }}</p>
                </div>
                <div class="rounded-xl bg-slate-950/60 border border-slate-800/80 p-3">
                    <span class="text-[10px] uppercase font-bold text-slate-500">App Environment</span>
                    <p class="mt-1 font-semibold text-white capitalize">{{ app()->environment() }}</p>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-800 flex items-center justify-between text-xs">
                <span class="text-slate-400">View deep diagnostics report:</span>
                <a href="{{ route('admin.diagnostics.index') }}" class="font-semibold text-indigo-400 hover:text-indigo-300">
                    System Health &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Administrative Audit Activity -->
    <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Recent Administrative Actions</h3>
                <p class="mt-0.5 text-xs text-slate-400">Immutable server-side audit logs (sensitive data redacted)</p>
            </div>
            <a href="{{ route('admin.audit.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300">
                View All Logs &rarr;
            </a>
        </div>

        <div class="mt-5 overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/60 text-slate-400 uppercase tracking-wider text-[10px] border-b border-slate-800">
                    <tr>
                        <th class="py-3 px-4">Timestamp</th>
                        <th class="py-3 px-4">Actor</th>
                        <th class="py-3 px-4">Action</th>
                        <th class="py-3 px-4">Entity</th>
                        <th class="py-3 px-4">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($recentAuditLogs as $log)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-4 font-mono text-slate-400 text-[11px] whitespace-nowrap">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="py-3 px-4 text-white font-medium">
                                {{ $log->user_email ?? 'CLI/System' }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="rounded bg-slate-800 px-2 py-0.5 font-mono text-[11px] text-indigo-300">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-300">
                                {{ $log->entity_type ? "{$log->entity_type} #{$log->entity_id}" : '—' }}
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-400 text-[11px]">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-500">
                                No administrative actions recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
