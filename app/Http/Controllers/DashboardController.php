<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // --- 1. Ambil Data Universal (Dibutuhkan oleh semua peran) ---
        $omsetHariIni = DB::table('transaksis')->where('status', 'bayar')->whereDate('created_at', today())->sum('total');
        $trxHariIni = DB::table('transaksis')->where('status', 'bayar')->whereDate('created_at', today())->count();
        $mejaKosong = Meja::whereIn('status', ['kosong', 'tersedia'])->count();
        $mejaTerpakai = Meja::where('status', 'terpakai')->count();
        $role = Auth::user()->role;

        // --- 2. Inisialisasi Variabel Spesifik Peran ---
        $drafts = collect();
        $recentPaid = collect();
        $jumlahMenu = 0;
        $jumlahMeja = 0;
        $topMenus = collect();

        // --- 3. Ambil Data Spesifik Berdasarkan Peran ---
        switch ($role) {
            case 'waiter':
                $drafts = DB::table('transaksis as t')
                    ->join('mejas as m', 'm.id', '=', 't.meja_id')
                    ->where('t.status', 'draft')
                    ->select('t.id', 'm.kode as meja', 't.total', 't.created_at')
                    ->orderByDesc('t.id')->limit(5)->get();
                break;

            case 'kasir':
                $drafts = DB::table('transaksis as t')
                    ->join('mejas as m', 'm.id', '=', 't.meja_id')
                    ->where('t.status', 'draft')
                    ->select('t.id', 'm.kode as meja', 't.total', 't.created_at')
                    ->orderByDesc('t.id')->limit(5)->get();
                $recentPaid = DB::table('transaksis as t')
                    ->join('mejas as m', 'm.id', '=', 't.meja_id')
                    ->where('t.status', 'bayar')
                    ->select('t.id', 'm.kode as meja', 't.total', 't.created_at')
                    ->orderByDesc('t.updated_at')->limit(5)->get();
                break;

            case 'administrator':
            case 'owner':
                $jumlahMenu = Menu::where('aktif', true)->count();
                $jumlahMeja = Meja::count();
                $drafts = DB::table('transaksis')->where('status', 'draft')->get(); // Untuk .count() di view
                $recentPaid = DB::table('transaksis as t')
                    ->join('mejas as m', 'm.id', '=', 't.meja_id')
                    ->where('t.status', 'bayar')
                    ->select('t.id', 'm.kode as meja', 't.total', 't.created_at')
                    ->orderByDesc('t.updated_at')->limit(5)->get();
                $topMenus = DB::table('pesanans as p')
                    ->join('menus as m', 'p.menu_id', '=', 'm.id')
                    ->selectRaw('m.nama, SUM(p.jumlah) as jml, SUM(p.subtotal) as omzet')
                    ->groupBy('m.nama')
                    ->orderByDesc('jml')
                    ->limit(5)->get();
                break;
        }

        // --- 4. Kirim Semua Data ke View Dashboard ---
        return view('dashboard', compact(
            'omsetHariIni',
            'trxHariIni',
            'mejaKosong',
            'mejaTerpakai',
            'role',
            'drafts',
            'recentPaid',
            'jumlahMenu',
            'jumlahMeja',
            'topMenus'
        ));
    }
}
