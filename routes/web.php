<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdPlacementController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContentPageController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiagnosticController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ToolController as AdminToolController;
use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Global & Technical Routes
|--------------------------------------------------------------------------
*/

// Root URL redirects to canonical default English locale with Google Tag
Route::get('/', function () {
    $target = route('home', ['locale' => 'en']);
    $gaId = (string) (\App\Models\SiteSetting::get('analytics_measurement_id') ?: env('ANALYTICS_MEASUREMENT_ID', 'G-HJ4N0PWKP7'));
    $html = '<!DOCTYPE html><html><head><meta charset="UTF-8">' .
            '<script async src="https://www.googletagmanager.com/gtag/js?id=' . e($gaId) . '"></script>' .
            '<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag("js",new Date());gtag("config","' . e($gaId) . '");</script>' .
            '<meta http-equiv="refresh" content="0;url=' . e($target) . '">' .
            '<title>Redirecting...</title></head><body>Redirecting to <a href="' . e($target) . '">' . e($target) . '</a></body></html>';
    return response($html, 302, ['Location' => $target]);
})->name('root');

// XML Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

/*
|--------------------------------------------------------------------------
| Localized Public Routes (en, bn)
|--------------------------------------------------------------------------
*/
Route::prefix('{locale}')
    ->where(['locale' => 'en|bn'])
    ->middleware(['setLocale'])
    ->group(function () {

        // Homepage
        Route::get('/', [HomeController::class, 'index'])->name('home');

        // Tools Directory & Categories
        Route::get('/tools', [DirectoryController::class, 'index'])->name('tools.index');
        Route::get('/tools/category/{category}', [DirectoryController::class, 'category'])->name('tools.category');

        // Individual Tool Routes
        Route::get('/tools/{slug}', [ToolController::class, 'show'])->name('tools.show');

        // Informational Pages
        Route::get('/about', [PageController::class, 'about'])->name('pages.about');
        Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
        Route::post('/contact', [PageController::class, 'submitContact'])->name('pages.contact.submit');
        Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('pages.privacy');
        Route::get('/terms-of-service', [PageController::class, 'terms'])->name('pages.terms');
        Route::get('/disclaimer', [PageController::class, 'disclaimer'])->name('pages.disclaimer');

        // Dynamic CMS / Guide Pages
        Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');
    });

/*
|--------------------------------------------------------------------------
| Protected Admin Authentication & Management Routes (Phase 2)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    // Unauthenticated Guest Login
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::get('/auth/login', fn () => redirect()->route('admin.login'))->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Protected Admin Area (Requires authenticated administrator)
    Route::middleware(['admin'])->group(function () {
        Route::get('/', fn () => redirect()->route('admin.dashboard'));
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Tool Management
        Route::get('/tools', [AdminToolController::class, 'index'])->name('admin.tools.index');
        Route::get('/tools/{tool}/edit', [AdminToolController::class, 'edit'])->name('admin.tools.edit');
        Route::put('/tools/{tool}', [AdminToolController::class, 'update'])->name('admin.tools.update');
        Route::post('/tools/{tool}/toggle', [AdminToolController::class, 'toggleStatus'])->name('admin.tools.toggle');

        // Category Management
        Route::resource('categories', CategoryController::class, ['as' => 'admin'])->except(['show']);

        // Content Pages Management (CMS)
        Route::resource('content', ContentPageController::class, [
            'as' => 'admin',
            'parameters' => ['content' => 'page'],
        ])->except(['show']);

        // Advertisement Placements
        Route::get('/ads', [AdPlacementController::class, 'index'])->name('admin.ads.index');
        Route::get('/ads/{placement}/edit', [AdPlacementController::class, 'edit'])->name('admin.ads.edit');
        Route::put('/ads/{placement}', [AdPlacementController::class, 'update'])->name('admin.ads.update');
        Route::post('/ads/{placement}/toggle', [AdPlacementController::class, 'toggle'])->name('admin.ads.toggle');

        // Site Settings & SEO
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');

        // Media Library
        Route::get('/media', [MediaController::class, 'index'])->name('admin.media.index');
        Route::post('/media', [MediaController::class, 'store'])->name('admin.media.store');
        Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('admin.media.destroy');

        // Audience Analytics
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics.index');

        // Audit Trail
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('admin.audit.index');

        // Diagnostics
        Route::get('/diagnostics', [DiagnosticController::class, 'index'])->name('admin.diagnostics.index');
    });
});
