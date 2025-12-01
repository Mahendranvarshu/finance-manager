<?php

use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CollectorAuthController;
use App\Http\Controllers\CollectorCollectionController;
use App\Http\Controllers\CollectorController;
use App\Http\Controllers\CollectorDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('parties', PartyController::class);
Route::resource('collectors', CollectorController::class);
Route::resource('collections', CollectionController::class);

// Reports
Route::get('/report/daily', [ReportController::class, 'daily'])->name('report.daily');
Route::get('/report/weekly', [ReportController::class, 'weekly'])->name('report.weekly');
Route::get('/report/monthly', [ReportController::class, 'monthly'])->name('report.monthly');

// Collector Authentication Routes
Route::prefix('collector')->name('collector.')->group(function () {
    // Public routes
    Route::get('/login', [CollectorAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CollectorAuthController::class, 'login']);
    Route::post('/logout', [CollectorAuthController::class, 'logout'])->name('logout');

    // Protected routes (require collector authentication)
    Route::middleware('auth:collector')->group(function () {
        Route::get('/dashboard', [CollectorDashboardController::class, 'index'])->name('dashboard');
        Route::resource('parties', PartyController::class);
        // Collection routes
        Route::get('/collection/create', [CollectorCollectionController::class, 'create'])->name('collection.create');
        Route::post('/collection/store', [CollectorCollectionController::class, 'store'])->name('collection.store');
        Route::get('/collection', [CollectorCollectionController::class, 'index'])->name('collection.index');
        Route::get('/collection/report', [CollectorCollectionController::class, 'report'])->name('collection.report');
        Route::get('/collection/export-excel', [CollectorCollectionController::class, 'exportExcel'])->name('collection.export-excel');
        Route::get('/collection/export-csv', [CollectorCollectionController::class, 'exportCsv'])->name('collection.export-csv');
    });
});

