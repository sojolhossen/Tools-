<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class DiagnosticController extends Controller
{
    public function index(): View
    {
        // 1. Database check
        $dbStatus = 'Connected';
        $dbDriver = 'unknown';
        try {
            DB::connection()->getPdo();
            $dbDriver = DB::getDriverName();
        } catch (\Throwable $e) {
            $dbStatus = 'Failed to connect: ' . $e->getMessage();
        }

        // 2. Storage writability checks
        $storageFrameworkWritable = File::isWritable(storage_path('framework'));
        $storageLogsWritable = File::isWritable(storage_path('logs'));
        $storageAppWritable = File::isWritable(storage_path('app'));

        // 3. Configurations (Safe non-secret keys)
        $cacheDriver = config('cache.default');
        $queueDriver = config('queue.default');
        $sessionDriver = config('session.driver');
        $mailDriver = config('mail.default');

        // 4. Integrations
        $analyticsEnabled = (bool) SiteSetting::get('analytics_enabled', false);
        $analyticsId = (string) SiteSetting::get('analytics_measurement_id', '');
        $analyticsStatus = ($analyticsEnabled && !empty($analyticsId)) ? 'Configured & Active' : 'Not configured';

        $adsenseEnabled = (bool) SiteSetting::get('adsense_enabled', false);
        $adsensePub = (string) SiteSetting::get('adsense_publisher_id', '');
        $adsenseStatus = ($adsenseEnabled && !empty($adsensePub)) ? 'Configured & Active' : 'Not configured';

        $checks = [
            'Runtime' => [
                'PHP Version' => PHP_VERSION,
                'Laravel Framework' => app()->version(),
                'Server Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'PHP CLI / Web Server',
                'Application Environment' => app()->environment(),
                'Debug Mode' => config('app.debug') ? 'Enabled (local only)' : 'Disabled (Production Safe)',
            ],
            'Database & Persistence' => [
                'Connection Status' => $dbStatus,
                'Driver Engine' => strtoupper($dbDriver),
            ],
            'Filesystem Permissions' => [
                'storage/framework' => $storageFrameworkWritable ? 'Writable (OK)' : 'Read-only (Error)',
                'storage/logs' => $storageLogsWritable ? 'Writable (OK)' : 'Read-only (Error)',
                'storage/app' => $storageAppWritable ? 'Writable (OK)' : 'Read-only (Error)',
            ],
            'Queue & Drivers' => [
                'Cache Store' => $cacheDriver,
                'Queue Connection' => $queueDriver,
                'Session Driver' => $sessionDriver,
                'Outbound Mail' => $mailDriver,
            ],
            'Integrations' => [
                'Google Analytics 4' => $analyticsStatus,
                'Google AdSense' => $adsenseStatus,
            ],
        ];

        return view('admin.diagnostics.index', compact('checks'));
    }
}
