<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Items (Inventory)
    Route::resource('items', ItemController::class);

    // Barang Masuk
    Route::resource('stock-in', StockInController::class)->except(['edit', 'update']);

    // Barang Keluar
    Route::resource('stock-out', StockOutController::class)->except(['edit', 'update']);

    // Peminjaman
    Route::resource('borrowings', BorrowingController::class)->except(['edit', 'update']);
    Route::post('borrowings/{borrowing}/approve', [BorrowingController::class, 'approve'])->name('borrowings.approve');
    Route::post('borrowings/{borrowing}/reject',  [BorrowingController::class, 'reject'])->name('borrowings.reject');
    Route::post('borrowings/{borrowing}/return',  [BorrowingController::class, 'return'])->name('borrowings.return');

    // Reports
    Route::get('reports',              [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export-pdf',   [ReportController::class, 'exportPdf'])->name('reports.pdf');
    Route::get('reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.excel');

    // Users - admin only
    Route::resource('users', UserController::class)->except(['show']);
});
