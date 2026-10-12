@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Header Banner & Time Filter -->
    <div class="rounded-2xl bg-gradient-to-r from-indigo-900/40 via-purple-900/30 to-slate-900 border border-indigo-500/20 p-6 sm:p-8 backdrop-blur">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        {{ $realtimeVisitors }} Active now (last 30m)
                    </span>
                    <span class="text-xs text-slate-400">• Privacy-First Analytics</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">Audience & Visitor Intelligence</h1>
                <p class="mt-1 text-xs sm:text-sm text-slate-300">
                    Real-time traffic metrics, top tools usage, referral sources, and device demographics.
                </p>
            </div>

            <!-- Time Filter Tabs -->
            <div class="flex items-center gap-1.5 bg-slate-950/80 p-1.5 rounded-xl border border-slate-800">
                <a href="{{ route('admin.analytics.index', ['period' => 'today']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $period === 'today' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-400 hover:text-white' }}">
                    Today
                </a>
                <a href="{{ route('admin.analytics.index', ['period' => '7d']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $period === '7d' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-400 hover:text-white' }}">
                    Last 7 Days
                </a>
                <a href="{{ route('admin.analytics.index', ['period' => '30d']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $period === '30d' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-400 hover:text-white' }}">
                    Last 30 Days
                </a>
                <a href="{{ route('admin.analytics.index', ['period' => 'all']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $period === 'all' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-400 hover:text-white' }}">
                    All Time
                </a>
            </div>
        </div>
    </div>

    <!-- 4 KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Unique Visitors -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Unique Visitors</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-500/10 text-indigo-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-black text-white">{{ number_format($uniqueVisitors) }}</span>
                <span class="text-xs text-indigo-400 font-medium">real humans</span>
            </div>
            <p class="mt-1 text-[11px] text-slate-500">De-duplicated daily unique visitor hashes</p>
        </div>

        <!-- Total Pageviews -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Pageviews</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-500/10 text-sky-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-black text-white">{{ number_format($totalPageviews) }}</span>
                <span class="text-xs text-sky-400 font-medium">impressions</span>
            </div>
            <p class="mt-1 text-[11px] text-slate-500">Avg. {{ $avgViewsPerVisitor }} views per visitor</p>
        </div>

        <!-- Real-Time Activity -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Live Concurrency</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500/10 text-emerald-400">
                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-black text-emerald-400">{{ $realtimeVisitors }}</span>
                <span class="text-xs text-slate-400">in last 30 mins</span>
            </div>
            <p class="mt-1 text-[11px] text-slate-500">Live audience browsing right now</p>
        </div>

        <!-- Bot Traffic Filtered -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Crawlers Isolated</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-500/10 text-amber-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" /></svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-black text-white">{{ number_format($botsFiltered) }}</span>
                <span class="text-xs text-amber-400 font-medium">bots ignored</span>
            </div>
            <p class="mt-1 text-[11px] text-slate-500">Search bots filtered to protect stat accuracy</p>
        </div>
    </div>

    <!-- Timeline Chart (Visual Trend) -->
    <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-base font-bold text-white">Traffic Activity Trend</h2>
                <p class="text-xs text-slate-400">Daily breakdown of Pageviews and Unique Visitors</p>
            </div>
            <div class="flex items-center gap-4 text-xs font-semibold">
                <span class="flex items-center gap-1.5 text-indigo-400">
                    <span class="w-3 h-3 rounded bg-indigo-500"></span> Pageviews
                </span>
                <span class="flex items-center gap-1.5 text-cyan-400">
                    <span class="w-3 h-3 rounded bg-cyan-400"></span> Unique Visitors
                </span>
            </div>
        </div>

        @php
            $maxVal = 1;
            foreach ($timelineData as $point) {
                if ($point['views'] > $maxVal) $maxVal = $point['views'];
            }
        @endphp

        <!-- CSS / SVG Bar Chart -->
        <div class="w-full overflow-x-auto pt-4 pb-2">
            <div class="min-w-[600px] flex items-end gap-3 h-48 border-b border-slate-800 px-2">
                @foreach($timelineData as $point)
                    @php
                        $viewsHeight = max(4, round(($point['views'] / $maxVal) * 160));
                        $uniquesHeight = max(2, round(($point['uniques'] / $maxVal) * 160));
                    @endphp
                    <div class="flex-1 flex flex-col items-center gap-1.5 group relative">
                        <!-- Tooltip -->
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none absolute -top-12 z-10 bg-slate-950 border border-slate-700 px-2.5 py-1 rounded-lg text-[10px] text-white whitespace-nowrap shadow-lg">
                            <span class="font-bold text-indigo-300">{{ $point['label'] }}:</span> {{ $point['views'] }} views ({{ $point['uniques'] }} unique)
                        </div>

                        <!-- Twin Bars -->
                        <div class="w-full flex items-end justify-center gap-1">
                            <div class="w-2.5 sm:w-3.5 bg-indigo-500/80 hover:bg-indigo-400 rounded-t-sm transition-all" style="height: {{ $viewsHeight }}px"></div>
                            <div class="w-2.5 sm:w-3.5 bg-cyan-400/80 hover:bg-cyan-300 rounded-t-sm transition-all" style="height: {{ $uniquesHeight }}px"></div>
                        </div>

                        <!-- X Axis Label -->
                        <span class="text-[10px] font-mono text-slate-500 group-hover:text-slate-300 truncate w-full text-center">
                            {{ $point['label'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 2 Column Breakdown Grid: Top Tools & Acquisition -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Visited Tools & Pages -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">Top Pages & Tools</h2>
                <span class="text-xs text-slate-500">{{ count($topPages) }} entries</span>
            </div>

            @if($topPages->count() > 0)
                <div class="space-y-3">
                    @foreach($topPages as $page)
                        @php
                            $pagePct = $totalPageviews > 0 ? round(($page->views / $totalPageviews) * 100, 1) : 0;
                        @endphp
                        <div class="rounded-xl bg-slate-950/60 border border-slate-800/80 p-3 space-y-1.5">
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2 truncate">
                                    <span class="font-mono text-indigo-400 truncate">/{{ $page->path }}</span>
                                    @if($page->tool_slug)
                                        <span class="rounded bg-indigo-500/20 px-1.5 py-0.5 text-[10px] font-semibold text-indigo-300">Tool</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="font-bold text-white">{{ number_format($page->views) }} views</span>
                                    <span class="text-[11px] text-slate-500">({{ $page->uniques }} unique)</span>
                                </div>
                            </div>
                            <!-- Bar -->
                            <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ $pagePct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-xs text-slate-500">
                    No visitor logs recorded for this period yet.
                </div>
            @endif
        </div>

        <!-- Traffic Sources / Referrers -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">Acquisition & Sources</h2>
                <span class="text-xs text-slate-500">Referral channels</span>
            </div>

            @if($topSources->count() > 0)
                <div class="space-y-3">
                    @foreach($topSources as $source)
                        @php
                            $sourcePct = $totalPageviews > 0 ? round(($source->views / $totalPageviews) * 100, 1) : 0;
                        @endphp
                        <div class="rounded-xl bg-slate-950/60 border border-slate-800/80 p-3 space-y-1.5">
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-slate-200">{{ $source->referrer_source ?: 'Direct' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-white">{{ number_format($source->views) }}</span>
                                    <span class="text-[11px] text-slate-400">({{ $sourcePct }}%)</span>
                                </div>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $sourcePct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-xs text-slate-500">
                    No source data recorded yet.
                </div>
            @endif
        </div>
    </div>

    <!-- 3 Column Breakdown: Devices, OS, Browsers -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Devices -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Device Types</h3>
            <div class="space-y-2.5">
                @forelse($devices as $dev)
                    @php $devPct = $totalPageviews > 0 ? round(($dev->count / $totalPageviews) * 100, 1) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-xs font-medium text-slate-300 mb-1">
                            <span class="capitalize">{{ $dev->device_type }}</span>
                            <span class="text-slate-400">{{ $dev->count }} ({{ $devPct }}%)</span>
                        </div>
                        <div class="w-full bg-slate-800 rounded-full h-1.5">
                            <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ $devPct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-500">No device data</p>
                @endforelse
            </div>
        </div>

        <!-- Operating Systems -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Operating Systems</h3>
            <div class="space-y-2.5">
                @forelse($operatingSystems as $osItem)
                    @php $osPct = $totalPageviews > 0 ? round(($osItem->count / $totalPageviews) * 100, 1) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-xs font-medium text-slate-300 mb-1">
                            <span>{{ $osItem->os }}</span>
                            <span class="text-slate-400">{{ $osItem->count }} ({{ $osPct }}%)</span>
                        </div>
                        <div class="w-full bg-slate-800 rounded-full h-1.5">
                            <div class="bg-sky-500 h-1.5 rounded-full" style="width: {{ $osPct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-500">No OS data</p>
                @endforelse
            </div>
        </div>

        <!-- Browsers & Language -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-5">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Top Browsers</h3>
            <div class="space-y-2.5">
                @forelse($browsers as $brw)
                    @php $brwPct = $totalPageviews > 0 ? round(($brw->count / $totalPageviews) * 100, 1) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-xs font-medium text-slate-300 mb-1">
                            <span>{{ $brw->browser }}</span>
                            <span class="text-slate-400">{{ $brw->count }} ({{ $brwPct }}%)</span>
                        </div>
                        <div class="w-full bg-slate-800 rounded-full h-1.5">
                            <div class="bg-violet-500 h-1.5 rounded-full" style="width: {{ $brwPct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-500">No browser data</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Live Stream: Recent Visitor Activity -->
    <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">Recent Live Visitor Stream</h2>
            </div>
            <span class="text-xs text-slate-500">Last 15 requests</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-800 text-[11px] uppercase font-bold text-slate-400">
                        <th class="py-2.5 px-3">Path</th>
                        <th class="py-2.5 px-3">Device / OS</th>
                        <th class="py-2.5 px-3">Browser</th>
                        <th class="py-2.5 px-3">Referrer</th>
                        <th class="py-2.5 px-3 text-right">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 font-mono text-[11px]">
                    @forelse($recentVisitors as $visit)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-2.5 px-3 font-semibold text-indigo-300 truncate max-w-xs">
                                /{{ $visit->path }}
                            </td>
                            <td class="py-2.5 px-3 text-slate-300">
                                <span class="capitalize">{{ $visit->device_type }}</span> • {{ $visit->os }}
                            </td>
                            <td class="py-2.5 px-3 text-slate-300">
                                {{ $visit->browser }}
                            </td>
                            <td class="py-2.5 px-3 text-slate-400 truncate max-w-xs">
                                {{ $visit->referrer_source }}
                            </td>
                            <td class="py-2.5 px-3 text-right text-slate-500 whitespace-nowrap">
                                {{ $visit->created_at ? $visit->created_at->diffForHumans() : 'Just now' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500 font-sans">
                                Waiting for live visits... Start browsing the site to see real-time updates!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- External Google Analytics 4 (GA4) Integration Banner -->
    <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-bold text-white">Google Analytics 4 (GA4) Dual-Tracking</h3>
                    @if($gaEnabled && !empty($gaId))
                        <span class="rounded-full bg-emerald-500/20 px-2 py-0.5 text-[10px] font-bold text-emerald-400 border border-emerald-500/30">Active: {{ $gaId }}</span>
                    @else
                        <span class="rounded-full bg-slate-800 px-2 py-0.5 text-[10px] font-bold text-slate-400">Optional / Disabled</span>
                    @endif
                </div>
                <p class="text-xs text-slate-400 max-w-2xl leading-relaxed">
                    Our built-in analytics engine works 100% reliably without external dependencies or ad-blocker blocking. If you also want to synchronize with Google Analytics 4, you can enable it in <a href="{{ route('admin.settings.index') }}" class="text-indigo-400 underline hover:text-indigo-300">System Settings</a> with your Measurement ID.
                </p>
            </div>
            <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-colors shrink-0 text-center">
                Configure GA4
            </a>
        </div>
    </div>
</div>
@endsection
