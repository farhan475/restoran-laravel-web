<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\TransaksiController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(
    function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::middleware('role:administrator,owner')->group(function () {
            Route::resource('menu', MenuController::class)->except('show');
        });

        Route::middleware('role:administrator,waiter')->group(function () {
            Route::resource('meja', MejaController::class)->except('show');
        });

        Route::middleware('role:waiter')->group(function () {
            Route::post('transaksi/open', [TransaksiController::class, 'open']);
            Route::post('transaksi/add-item', [TransaksiController::class, 'addItem']);
        });

        Route::middleware('role:kasir')->group(function () {
            Route::post('transaksi/bayar', [TransaksiController::class, 'bayar']);
            Route::get('laporan/harian', [TransaksiController::class, 'laporanHarian']);
        });

        Route::middleware('role:owner,administrator')->group(function () {
            Route::get('laporan/rekap', [TransaksiController::class, 'rekap']);
        });
        // Waiter: UI order
        Route::middleware('role:waiter')->group(function () {
            Route::get('order', [TransaksiController::class, 'orderIndex'])->name('order.index');
            Route::get('order/meja/{meja}', [TransaksiController::class, 'orderPOS'])->name('order.pos');
            Route::post('transaksi/open', [TransaksiController::class, 'open']);
            Route::post('transaksi/add-item', [TransaksiController::class, 'addItem']);
        });

        // Kasir: UI pembayaran
        Route::middleware('role:kasir')->group(function () {
            Route::get('kasir', [TransaksiController::class, 'kasirIndex'])->name('kasir.index');
            Route::get('kasir/{transaksi}', [TransaksiController::class, 'kasirBayar'])->name('kasir.bayar');
            Route::post('transaksi/bayar', [TransaksiController::class, 'bayar']);
            Route::get('laporan/harian', [TransaksiController::class, 'laporanHarian'])->name('laporan.harian');
        });

        // Owner/Admin: Rekap
        Route::middleware('role:owner,administrator')->group(function () {
            Route::get('laporan/rekap', [TransaksiController::class, 'rekap'])->name('laporan.rekap');
        });
    }


);
