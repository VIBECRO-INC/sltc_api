<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\Public\ContentController;
use App\Http\Controllers\Api\Public\QuoteController;
use App\Http\Controllers\Api\Public\ContactController;
use App\Http\Controllers\Api\Admin\AdminController;

Route::prefix('v1')->group(function () {
    Route::get('/health', HealthController::class);
    // Public read API
    Route::get('/home', [ContentController::class, 'home']);
    Route::get('/services', [ContentController::class, 'services']);
    Route::get('/services/{service:slug}', [ContentController::class, 'service']);
    Route::get('/equipment', [ContentController::class, 'equipment']);
    Route::get('/equipment/{equipment:slug}', [ContentController::class, 'equipmentShow']);
    Route::get('/projects', [ContentController::class, 'projects']);
    Route::get('/projects/{project:slug}', [ContentController::class, 'project']);
    Route::get('/team', [ContentController::class, 'team']);
    Route::get('/references', [ContentController::class, 'references']);
    Route::get('/products', [ContentController::class, 'products']);
    Route::get('/news', [ContentController::class, 'news']);
    Route::get('/news/{article:slug}', [ContentController::class, 'article']);
    Route::get('/testimonials', [ContentController::class, 'testimonials']);
    Route::get('/gallery', [ContentController::class, 'gallery']);
    Route::get('/pages/{page:slug}', [ContentController::class, 'page']);
    Route::get('/settings', [ContentController::class, 'settings']);
    Route::get('/seo/{slug}', [ContentController::class, 'seo']);

    // Public write endpoints. Rate-limit these in production.
    Route::middleware('throttle:10,1')->group(function () {
        Route::post('/quotes', [QuoteController::class, 'store']);
        Route::post('/contact', [ContactController::class, 'store']);
    });

    // Page view tracking (fire-and-forget, high volume)
    Route::post('/track', [ContentController::class, 'track']);

    // Authentication
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);
            Route::put('/profile', [AuthController::class, 'updateProfile']);
            Route::put('/password', [AuthController::class, 'updatePassword']);
        });
    });

    // Protected back-office API
    Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/notifications', [AdminController::class, 'notifications']);
Route::post('/notifications/read', [AdminController::class, 'markNotificationsRead']);
        Route::get('/analytics', [AdminController::class, 'analytics']);
        Route::post('/upload', [AdminController::class, 'upload']);

        $resources = 'services|equipment|projects|team|references|products|news|testimonials|gallery|pages';

        Route::get('/{resource}', [AdminController::class, 'index'])->where('resource', $resources);
        Route::post('/{resource}', [AdminController::class, 'store'])->where('resource', $resources);
        Route::get('/{resource}/{id}', [AdminController::class, 'show'])->where('resource', $resources);
        Route::match(['put', 'patch'], '/{resource}/{id}', [AdminController::class, 'update'])->where('resource', $resources);
        Route::delete('/{resource}/{id}', [AdminController::class, 'destroy'])->where('resource', $resources);

        Route::get('/quotes', [AdminController::class, 'quotes']);
        Route::get('/quotes/{quote}', [AdminController::class, 'quote']);
        Route::patch('/quotes/{quote}/status', [AdminController::class, 'quoteStatus']);

        Route::get('/contacts', [AdminController::class, 'contacts']);
        Route::get('/contacts/{contact}', [AdminController::class, 'contact']);
        Route::patch('/contacts/{contact}/read', [AdminController::class, 'contactRead']);

        Route::get('/seo', [AdminController::class, 'seoIndex']);
        Route::put('/seo/{seo}', [AdminController::class, 'seoUpdate']);

        Route::get('/settings', [AdminController::class, 'settings']);
        Route::put('/settings', [AdminController::class, 'settingsUpdate']);
    });
});
