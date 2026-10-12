<?php

declare(strict_types=1);

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

// Root URL redirects to canonical default English locale
Route::get('/', function () {
    return redirect()->to('/en', 302);
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
    });

/*
|--------------------------------------------------------------------------
| Future Administrative Routes (Phase 2 Architecture Placeholder)
|--------------------------------------------------------------------------
|
| In Phase 2, administrative routes will be guarded by strict authentication,
| two-factor challenges, audit logs, and role-based permissions:
|
| Route::prefix('admin')->middleware(['auth', 'verified', 'admin'])->group(...);
|
*/
