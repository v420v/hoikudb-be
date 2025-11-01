<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PreschoolController;
use App\Http\Controllers\DataProviderController;

Route::middleware('guest')->group(function () {
    Route::get('/preschool/stats', [PreschoolController::class, 'getStatsJson'])->name('preschool.stats');
});

Route::middleware('basic.auth')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/', [PreschoolController::class, 'index'])->name('preschool.index');
        Route::get('/preschool/import', [PreschoolController::class, 'import'])->name('preschool.import');
        Route::post('/preschool/import', [PreschoolController::class, 'importStore'])->name('preschool.import.store');
        Route::get('/preschool/import-history/{preschoolStatsImportHistoryId}', [PreschoolController::class, 'importHistory'])->name('preschool.import.history');

        // データプロバイダー
        Route::resource('data-provider', DataProviderController::class)->except(['show']);
        
        // ファイル設定管理
        Route::prefix('data-provider/{dataProviderId}/file-config')->name('data-provider.file-config.')->group(function () {
            Route::get('/create', [DataProviderController::class, 'createFileConfig'])->name('create');
            Route::post('/', [DataProviderController::class, 'storeFileConfig'])->name('store');
            Route::get('/{fileConfigId}/edit', [DataProviderController::class, 'editFileConfig'])->name('edit');
            Route::put('/{fileConfigId}', [DataProviderController::class, 'updateFileConfig'])->name('update');
            Route::delete('/{fileConfigId}', [DataProviderController::class, 'destroyFileConfig'])->name('destroy');
        });
    });
});
