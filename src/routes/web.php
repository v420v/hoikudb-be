<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PreschoolController;

// Basic認証を全体に適用
Route::middleware('basic.auth')->group(function () {
    Route::get('/preschool/stats', [PreschoolController::class, 'getStatsJson'])->name('preschool.stats');

    // 認証関連のルート
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 認証が必要なルート
    Route::middleware('auth')->group(function () {
        Route::get('/', [PreschoolController::class, 'index'])->name('preschool.index');
        Route::get('/preschool/import', [PreschoolController::class, 'import'])->name('preschool.import');
        Route::post('/preschool/import', [PreschoolController::class, 'importStore'])->name('preschool.import.store');
        Route::get('/preschool/import-history/{csvImportHistoryId}', [PreschoolController::class, 'importHistory'])->name('preschool.import.history');
    });
});
