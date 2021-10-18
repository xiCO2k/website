<?php

use App\Http\Controllers\Backend\AdminLinksController;
use App\Http\Controllers\Frontend\CrawlerController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\LinksController as UserLinksController;
use App\Http\Controllers\Frontend\SocialLoginController;
use App\Http\Controllers\Landing\HomeController;
use App\Http\Controllers\Landing\LinksController as LandingLinksController;
use App\Http\Controllers\Landing\LinksRedirectController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome-pre-2021');

Route::get('/lander', [HomeController::class, 'index']);

Route::get('landing', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/links/redirect/{link:hash}', [LinksRedirectController::class, 'redirect'])->name('links.public.redirect');
Route::get('/links', [LandingLinksController::class, 'index'])->name('links.public');

/*
|--------------------------------------------------------------------------
| Social Login Auth
|--------------------------------------------------------------------------
*/
Route::get('login/{provider}/redirect', [SocialLoginController::class, 'redirect'])->name('social.redirect');
Route::get('login/{provider}/callback', [SocialLoginController::class, 'callback'])->name('social.callback');

/*
|--------------------------------------------------------------------------
| Users - Private Area Routes
|--------------------------------------------------------------------------
*/
Route::prefix('dashboard')->group(function () {
    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/crawler', [CrawlerController::class, 'search'])->name('crawler.search');
        Route::resource('links', UserLinksController::class)->middleware(['auth:sanctum', 'verified']);
    });
});

/*
|--------------------------------------------------------------------------
| Admins & Moderators - Manage Links
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->as('admin.')
    ->middleware(['role:admin', 'auth:sanctum', 'verified'])
    ->group(function () {
        Route::get('/', function () { dd('im admin'); })->name('dashboard');
        Route::resource('links', AdminLinksController::class);
        Route::get('links/{link}/status/{status}', [AdminLinksController::class, 'markAs'])->name('links.status');
    });
