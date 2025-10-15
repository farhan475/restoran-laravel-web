<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
    // Buka draft transaksi di meja
    public function open(Request $request)
    {
        $data = $request->validate(['meja_id' => ['required', 'exists:mejas,id']]);
        DB::statement('CALL sp_open_transaksi(?,?,@out_id)', [$data['meja_id'], Auth::id()]);
        $row = DB::select('SELECT @out_id AS id')[0];
        return response()->json(['transaksi_id' => $row->id]);
    }

    // Tambah item
    public function addItem(Request $request)
    {
        $data = $request->validate([
            'transaksi_id' => ['required', 'exists:transaksis,id'],
            'menu_id'      => ['required', 'exists:menus,id'],
            'qty'          => ['required', 'integer', 'min:1'],
        ]);
        DB::statement('CALL sp_add_item(?,?,?)', [$data['transaksi_id'], $data['menu_id'], $data['qty']]);
        return response()->json(['ok' => true]);
    }

    // Bayar
    public function bayar(Request $request)
    {
        $data = $request->validate([
            'transaksi_id' => ['required', 'exists:transaksis,id'],
            'bayar'        => ['required', 'integer', 'min:0'],
        ]);
        DB::statement('CALL sp_bayar(?,?)', [$data['transaksi_id'], $data['bayar']]);
        return response()->json(['ok' => true]);
    }

    // UI waiter: daftar meja
    public function orderIndex()
    {
        $mejas = Meja::orderBy('kode')->get();
        return view('order.index', compact('mejas'));
    }

    // UI waiter: POS per meja
    public function orderPOS(Meja $meja)
    {
        DB::statement('CALL sp_open_transaksi(?,?,@out_id)', [$meja->id, Auth::id()]);
        $row = DB::select('SELECT @out_id AS id')[0];
        $transaksiId = $row->id;

        $menus = Menu::where('aktif', 1)->orderBy('nama')->get(['id', 'nama', 'harga']);

        $items = DB::table('pesanans as p')
            ->join('menus as mn', 'mn.id', '=', 'p.menu_id')
            ->where('p.transaksi_id', $transaksiId)
            ->select('p.id', 'mn.nama', 'p.jumlah', 'p.harga_satuan', 'p.subtotal')
            ->get();

        $total = DB::table('transaksis')->where('id', $transaksiId)->value('total');

        return view('order.pos', compact('meja', 'menus', 'items', 'total', 'transaksiId'));
    }

    // UI kasir: list draft
    public function kasirIndex()
    {
        $drafts = DB::table('transaksis as t')
            ->join('mejas as m', 'm.id', '=', 't.meja_id')
            ->where('t.status', 'draft') // qualify
            ->select('t.id', 'm.kode as meja', 't.total', 't.created_at')
            ->orderByDesc('t.id')
            ->paginate(12);
        return view('kasir.index', compact('drafts'));
    }

    // UI kasir: halaman bayar
    public function kasirBayar(Transaksi $transaksi)
    {
        abort_unless($transaksi->status === 'draft', 404);

        $meja = Meja::find($transaksi->meja_id);

        $items = DB::table('pesanans as p')
            ->join('menus as mn', 'mn.id', '=', 'p.menu_id')
            ->where('p.transaksi_id', $transaksi->id)
            ->select('mn.nama', 'p.jumlah', 'p.harga_satuan', 'p.subtotal')
            ->get();

        return view('kasir.bayar', [
            'transaksi' => $transaksi,
            'meja' => $meja,
            'items' => $items,
            'kembali' => 0
        ]);
    }

    // Laporan harian (tanpa join)
    public function laporanHarian(Request $request)
    {
        $tanggal = $request->validate(['tanggal' => ['nullable', 'date']])['tanggal'] ?? now()->toDateString();
        $rows = DB::table('transaksis')
            ->whereDate('created_at', $tanggal)
            ->where('status', 'dibayar')
            ->get();
        return view('laporan.harian', compact('rows', 'tanggal'));
    }

    // Rekap (tanpa join)
    public function rekap()
    {
        $rows = DB::table('transaksis')
            ->selectRaw('DATE(created_at) tgl, COUNT(*) trx, SUM(total) omset')
            ->where('status', 'dibayar')
            ->groupBy('tgl')
            ->orderByDesc('tgl')
            ->paginate(30);
        return view('laporan.rekap', compact('rows'));
    }
}
