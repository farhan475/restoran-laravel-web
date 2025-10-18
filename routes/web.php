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
|
| Di sini Anda dapat mendaftarkan rute web untuk aplikasi Anda. Rute-rute
| ini dimuat oleh RouteServiceProvider dan semuanya akan
| ditugaskan ke grup middleware "web". Buat sesuatu yang hebat!
|
*/

// --- Rute untuk Tamu (Guest) ---
// Hanya bisa diakses jika pengguna BELUM login.
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


// --- Rute untuk Pengguna yang Sudah Login ---
// Hanya bisa diakses jika pengguna SUDAH login.
Route::middleware('auth')->group(function () {
    // Rute umum untuk semua peran setelah login
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Rute Berdasarkan Peran (Role)
    |--------------------------------------------------------------------------
    */

    // Peran: Administrator & Owner
    // Hanya bisa diakses oleh pengguna dengan peran 'administrator' ATAU 'owner'.
    Route::middleware('role:administrator,owner')->group(function () {
        Route::resource('menu', MenuController::class)->except('show');
        Route::get('laporan/rekap', [TransaksiController::class, 'rekap'])->name('laporan.rekap');
    });

    // Peran: Administrator & Waiter
    // Hanya bisa diakses oleh pengguna dengan peran 'administrator' ATAU 'waiter'.
    Route::middleware('role:administrator,waiter')->group(function () {
        Route::resource('meja', MejaController::class)->except('show');
    });

    // Peran: Waiter
    // Hanya bisa diakses oleh pengguna dengan peran 'waiter'.
    Route::middleware('role:waiter')->group(function () {
        // UI untuk Order
        Route::get('order', [TransaksiController::class, 'orderIndex'])->name('order.index');
        Route::get('order/meja/{meja}', [TransaksiController::class, 'orderPOS'])->name('order.pos');
        Route::post('transaksi/add-items-bulk', [TransaksiController::class, 'addItemsBulk'])->name('transaksi.addItemsBulk');


        // Aksi terkait Transaksi untuk Waiter
        Route::post('transaksi/open', [TransaksiController::class, 'open'])->name('transaksi.open');
        Route::post('transaksi/add-item', [TransaksiController::class, 'addItem'])->name('transaksi.addItem');
    });

    // Peran: Kasir
    // Hanya bisa diakses oleh pengguna dengan peran 'kasir'.
    Route::middleware('role:kasir')->group(function () {
        // UI untuk Kasir
        Route::get('kasir', [TransaksiController::class, 'kasirIndex'])->name('kasir.index');
        Route::get('kasir/{transaksi}', [TransaksiController::class, 'kasirBayar'])->name('kasir.bayar');

        // Aksi terkait Transaksi untuk Kasir
        Route::post('transaksi/bayar', [TransaksiController::class, 'bayar'])->name('transaksi.bayar');

        // Laporan untuk Kasir
        Route::get('laporan/harian', [TransaksiController::class, 'laporanHarian'])->name('laporan.harian');
    });
});
