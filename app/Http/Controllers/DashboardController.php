<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $role  = Auth::user()->role;
        $today = now()->toDateString();

        // KPI tanpa join → aman
        $omsetHariIni = DB::table('transaksis')
            ->whereDate('created_at', $today)
            ->where('status', 'dibayar')
            ->sum('total');

        $trxHariIni = DB::table('transaksis')
            ->whereDate('created_at', $today)
            ->count();

        $mejaKosong   = DB::table('mejas')->where('status', 'kosong')->count();
        $mejaTerpakai = DB::table('mejas')->where('status', 'terpakai')->count();

        // Draft: qualify semua kolom
        $drafts = DB::table('transaksis as t')
            ->join('mejas as m', 'm.id', '=', 't.meja_id')
            ->where('t.status', 'draft')
            ->select('t.id', 'm.kode as meja', 't.total', 't.created_at')
            ->orderByDesc('t.id')
            ->limit(6)
            ->get();

        // Dibayar terakhir: qualify
        $recentPaid = DB::table('transaksis as t')
            ->join('mejas as m', 'm.id', '=', 't.meja_id')
            ->where('t.status', 'dibayar')
            ->select('t.id', 'm.kode as meja', 't.total', 't.created_at')
            ->orderByDesc('t.id')
            ->limit(6)
            ->get();

        // Top menu dari detail pesanan (tidak pakai status)
        $topMenus = DB::table('pesanans as p')
            ->join('menus as mn', 'mn.id', '=', 'p.menu_id')
            ->select('mn.nama', DB::raw('SUM(p.jumlah) as jml'), DB::raw('SUM(p.subtotal) as omzet'))
            ->groupBy('mn.nama')
            ->orderByDesc('jml')
            ->limit(5)
            ->get();

        $jumlahMenu = DB::table('menus')->count();
        $jumlahMeja = DB::table('mejas')->count();

        return view('dashboard', compact(
            'role',
            'omsetHariIni',
            'trxHariIni',
            'mejaKosong',
            'mejaTerpakai',
            'drafts',
            'recentPaid',
            'topMenus',
            'jumlahMenu',
            'jumlahMeja'
        ));
    }
}
