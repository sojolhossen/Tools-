<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use App\Models\SiteSetting;
use App\Support\Tools\ToolRegistry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(Request $request, ToolRegistry $toolRegistry): View
    {
        $period = $request->query('period', '7d');

        // Determine date range
        $today = Carbon::today();
        $startDate = match ($period) {
            'today' => $today->copy(),
            '30d' => $today->copy()->subDays(29),
            'all' => Carbon::createFromTimestamp(0),
            default => $today->copy()->subDays(6), // '7d'
        };
        $endDate = $today->copy();

        // Base query for humans in this period
        $query = PageView::humans();
        if ($period !== 'all') {
            $query->betweenDates($startDate->toDateString(), $endDate->toDateString());
        }

        // 1. KPI Summaries
        $totalPageviews = (clone $query)->count();
        $uniqueVisitors = (clone $query)->distinct('session_hash')->count('session_hash');
        $avgViewsPerVisitor = $uniqueVisitors > 0 ? round($totalPageviews / $uniqueVisitors, 1) : 0;

        // Real-time active in last 5 minutes (Industry standard live concurrency window)
        $realtimeVisitors = PageView::humans()
            ->activeRecent(5)
            ->distinct('session_hash')
            ->count('session_hash');

        // Bots filtered count
        $botsQuery = PageView::where('is_bot', true);
        if ($period !== 'all') {
            $botsQuery->whereDate('viewed_date', '>=', $startDate->toDateString())
                ->whereDate('viewed_date', '<=', $endDate->toDateString());
        }
        $botsFiltered = $botsQuery->count();

        // 2. Timeline Trend (Daily series)
        $timelineData = [];
        if ($period === 'today') {
            // Hourly series for today
            for ($h = 0; $h < 24; $h++) {
                $hourLabel = sprintf('%02d:00', $h);
                $timelineData[$hourLabel] = [
                    'label' => $hourLabel,
                    'views' => 0,
                    'uniques' => 0,
                ];
            }
            $driver = DB::getDriverName();
            $hourExpr = $driver === 'sqlite' ? "strftime('%H', created_at)" : "HOUR(created_at)";

            $hourlyRows = DB::table('page_views')
                ->where('is_bot', false)
                ->whereDate('viewed_date', $today->toDateString())
                ->select(
                    DB::raw("{$hourExpr} as hour"),
                    DB::raw('count(id) as views'),
                    DB::raw('count(distinct session_hash) as uniques')
                )
                ->groupBy('hour')
                ->get();

            foreach ($hourlyRows as $row) {
                $hourFormatted = sprintf('%02d:00', (int) $row->hour);
                if (isset($timelineData[$hourFormatted])) {
                    $timelineData[$hourFormatted]['views'] = (int) $row->views;
                    $timelineData[$hourFormatted]['uniques'] = (int) $row->uniques;
                }
            }
        } else {
            // Daily series
            $daysCount = match ($period) {
                '30d' => 30,
                'all' => 30, // Show last 30 days on chart for all-time
                default => 7,
            };
            for ($i = $daysCount - 1; $i >= 0; $i--) {
                $dateKey = $today->copy()->subDays($i)->toDateString();
                $timelineData[$dateKey] = [
                    'label' => Carbon::parse($dateKey)->format('M d'),
                    'views' => 0,
                    'uniques' => 0,
                ];
            }

            $dailyRows = (clone $query)
                ->select(
                    'viewed_date',
                    DB::raw('count(id) as views'),
                    DB::raw('count(distinct session_hash) as uniques')
                )
                ->groupBy('viewed_date')
                ->get();

            foreach ($dailyRows as $row) {
                $dateStr = $row->viewed_date instanceof Carbon ? $row->viewed_date->toDateString() : (string) $row->viewed_date;
                if (isset($timelineData[$dateStr])) {
                    $timelineData[$dateStr]['views'] = (int) $row->views;
                    $timelineData[$dateStr]['uniques'] = (int) $row->uniques;
                }
            }
        }

        // 3. Top Tools and Pages
        $topPages = (clone $query)
            ->select(
                'path',
                'tool_slug',
                DB::raw('count(id) as views'),
                DB::raw('count(distinct session_hash) as uniques')
            )
            ->groupBy('path', 'tool_slug')
            ->orderByDesc('views')
            ->take(10)
            ->get();

        // 4. Traffic Sources & Referrers
        $topSources = (clone $query)
            ->select(
                'referrer_source',
                DB::raw('count(id) as views')
            )
            ->groupBy('referrer_source')
            ->orderByDesc('views')
            ->take(8)
            ->get();

        // 5. Device Types Breakdown
        $devices = (clone $query)
            ->select('device_type', DB::raw('count(id) as count'))
            ->groupBy('device_type')
            ->orderByDesc('count')
            ->get();

        // 6. Operating Systems
        $operatingSystems = (clone $query)
            ->select('os', DB::raw('count(id) as count'))
            ->groupBy('os')
            ->orderByDesc('count')
            ->take(6)
            ->get();

        // 7. Browsers
        $browsers = (clone $query)
            ->select('browser', DB::raw('count(id) as count'))
            ->groupBy('browser')
            ->orderByDesc('count')
            ->take(6)
            ->get();

        // 8. Locales (English vs Bangla)
        $locales = (clone $query)
            ->select('locale', DB::raw('count(id) as count'))
            ->groupBy('locale')
            ->orderByDesc('count')
            ->get();

        // 9. Recent Real-Time Visitors Log
        $recentVisitors = PageView::humans()
            ->latest('id')
            ->take(15)
            ->get();

        // 10. External GA4 Configuration Status
        $gaEnabled = (bool) SiteSetting::get('analytics_enabled', false);
        $gaId = (string) SiteSetting::get('analytics_measurement_id', '');

        return view('admin.analytics.index', compact(
            'period',
            'totalPageviews',
            'uniqueVisitors',
            'avgViewsPerVisitor',
            'realtimeVisitors',
            'botsFiltered',
            'timelineData',
            'topPages',
            'topSources',
            'devices',
            'operatingSystems',
            'browsers',
            'locales',
            'recentVisitors',
            'gaEnabled',
            'gaId'
        ));
    }
}
