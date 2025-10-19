<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\TransaksiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Rute untuk Tamu (Guest) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


// --- Rute untuk Pengguna yang Sudah Login ---
Route::middleware('auth')->group(function () {
    // Rute umum
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Rute Berdasarkan Peran (Role)
    |--------------------------------------------------------------------------
    */

    // Peran: Administrator & Owner
    Route::middleware('role:administrator,owner')->group(function () {
        Route::resource('menu', MenuController::class)->except('show');
        Route::get('laporan/rekap', [TransaksiController::class, 'rekap'])->name('laporan.rekap');
        Route::get('laporan/rekap/pdf', [TransaksiController::class, 'pdfRekap'])->name('laporan.rekap.pdf');
    });

    // Peran: Administrator & Waiter
    Route::middleware('role:administrator,waiter')->group(function () {
        Route::resource('meja', MejaController::class)->except('show');
    });

    // Peran: Waiter
    Route::middleware('role:waiter')->group(function () {
        Route::get('order', [TransaksiController::class, 'orderIndex'])->name('order.index');
        Route::get('order/meja/{meja}', [TransaksiController::class, 'orderPOS'])->name('order.pos');
        Route::post('transaksi/open', [TransaksiController::class, 'open'])->name('transaksi.open');
        Route::post('transaksi/add-items-bulk', [TransaksiController::class, 'addItemsBulk'])->name('transaksi.addItemsBulk');
        Route::post('transaksi/add-item', [TransaksiController::class, 'addItem'])->name('transaksi.addItem'); // Rute lama
    });

    // Peran: Kasir (Hanya untuk aksi spesifik kasir)
    Route::middleware('role:kasir')->group(function () {
        Route::get('kasir', [TransaksiController::class, 'kasirIndex'])->name('kasir.index');
        Route::get('kasir/{transaksi}', [TransaksiController::class, 'kasirBayar'])->name('kasir.bayar');
        Route::post('transaksi/bayar', [TransaksiController::class, 'bayar'])->name('transaksi.bayar');
    });

    // Peran: Kasir & Administrator (Fitur bersama)
    Route::middleware('role:kasir,administrator')->group(function () {
        Route::get('kasir/transaksi/{transaksi}/print', [TransaksiController::class, 'printStruk'])->name('kasir.struk.print');
        Route::get('laporan/harian/export', [TransaksiController::class, 'exportLaporanHarian'])->name('laporan.harian.export');
    });

    // Peran: Kasir, Administrator, & Owner (Fitur Laporan Bersama)
    Route::middleware('role:kasir,administrator,owner')->group(function () {
        Route::get('laporan/harian', [TransaksiController::class, 'laporanHarian'])->name('laporan.harian');
        Route::get('laporan/harian/pdf', [TransaksiController::class, 'pdfLaporanHarian'])->name('laporan.harian.pdf');
        Route::get('kasir/transaksi/{transaksi}/print', [TransaksiController::class, 'printStruk'])->name('kasir.struk.print');
    });
});
