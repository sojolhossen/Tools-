<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdPlacement;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\ContentPage;
use App\Models\SiteSetting;
use App\Models\Tool;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Aggregate actual system metrics
        $totalTools = Tool::count();
        $activeTools = Tool::where('status', 'active')->count();
        $inactiveTools = $totalTools - $activeTools;
        $totalCategories = Category::count();
        $publishedPages = ContentPage::where('status', 'published')->count();
        $draftPages = ContentPage::where('status', 'draft')->count();
        $totalPlacements = AdPlacement::count();
        $activePlacements = AdPlacement::where('is_active', true)->count();

        // Recent audit events
        $recentAuditLogs = AuditLog::latest('id')->take(6)->get();

        // Integration statuses (honest evaluation)
        $analyticsEnabled = (bool) SiteSetting::get('analytics_enabled', false);
        $analyticsMeasurementId = (string) SiteSetting::get('analytics_measurement_id', '');
        $analyticsStatus = ($analyticsEnabled && !empty($analyticsMeasurementId)) ? 'Active' : 'Not configured';

        $adsenseEnabled = (bool) SiteSetting::get('adsense_enabled', false);
        $adsensePublisherId = (string) SiteSetting::get('adsense_publisher_id', '');
        $adsenseStatus = ($adsenseEnabled && !empty($adsensePublisherId)) ? 'Active' : 'Not configured';

        // Diagnostics snapshot
        $databaseDriver = DB::getDriverName();
        $phpVersion = PHP_VERSION;
        $laravelVersion = app()->version();

        return view('admin.dashboard.index', compact(
            'totalTools',
            'activeTools',
            'inactiveTools',
            'totalCategories',
            'publishedPages',
            'draftPages',
            'totalPlacements',
            'activePlacements',
            'recentAuditLogs',
            'analyticsStatus',
            'adsenseStatus',
            'databaseDriver',
            'phpVersion',
            'laravelVersion'
        ));
    }
}
