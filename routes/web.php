<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\SiteController;
use App\Http\Controllers\Admin\UiBlockController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public: redirect root to admin
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('admin.dashboard'));

/*
|--------------------------------------------------------------------------
| Public: per-site client pages
|--------------------------------------------------------------------------
*/
Route::get('/page/{site:slug}', [ClientController::class, 'show'])->name('client.page');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin (auth required)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    // Dashboard (site list)
    Route::get('/', [SiteController::class, 'index'])->name('dashboard');

    // Site management
    Route::post('sites',         [SiteController::class, 'store'])->name('sites.store');
    Route::delete('sites/{site}', [SiteController::class, 'destroy'])->name('sites.destroy');

    // Per-site block management
    Route::prefix('sites/{site}')->name('sites.')->group(function () {

        // Visual editor
        Route::get('visual-editor', [UiBlockController::class, 'visualEditor'])
            ->name('visual-editor');

        // Page background color
        Route::patch('background', [SiteController::class, 'updateBackground'])
            ->name('background.update');

        // AJAX helpers (must be before resource to avoid {uiBlock} matching "reorder")
        Route::post('ui-blocks/reorder', [UiBlockController::class, 'reorder'])
            ->name('ui-blocks.reorder');

        Route::patch('ui-blocks/{uiBlock}/toggle', [UiBlockController::class, 'toggle'])
            ->name('ui-blocks.toggle');

        Route::get('ui-blocks/{uiBlock}/render', [UiBlockController::class, 'renderBlock'])
            ->name('ui-blocks.render');

        // Full CRUD resource
        Route::resource('ui-blocks', UiBlockController::class);
    });
});
