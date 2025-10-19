<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Menu;
use App\Models\Transaksi;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\LaporanHarianExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    /**
     * [API] Buka draft transaksi di meja.
     * Digunakan oleh UI lain jika diperlukan.
     */
    public function open(Request $request)
    {
        $data = $request->validate(['meja_id' => ['required', 'exists:mejas,id']]);

        DB::statement('CALL sp_open_transaksi(?,?,@out_id)', [$data['meja_id'], Auth::id()]);
        $row = DB::selectOne('SELECT @out_id AS id');

        return response()->json(['transaksi_id' => $row->id]);
    }

    /**
     * [API] Tambah item ke dalam transaksi.
     * Digunakan oleh halaman POS melalui Fetch API.
     */
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

    /**
     * [API] Proses pembayaran transaksi.
     * Digunakan oleh halaman pembayaran kasir.
     */
    public function bayar(Request $request)
    {
        $data = $request->validate([
            'transaksi_id' => ['required', 'exists:transaksis,id'],
            'bayar'        => ['required', 'integer', 'min:0'],
        ]);

        DB::statement('CALL sp_bayar(?,?)', [$data['transaksi_id'], $data['bayar']]);
        return redirect()->route('kasir.index')->with('ok', 'Transaksi berhasil dibayar.');
    }
    // Tambahkan metode ini di dalam TransaksiController
    public function addItemsBulk(Request $request)
    {
        $data = $request->validate([
            'transaksi_id' => ['required', 'exists:transaksis,id'],
            'items'        => ['required', 'array'],
            'items.*.menu_id' => ['required', 'exists:menus,id'],
            'items.*.qty'     => ['required', 'integer', 'min:1'],
        ]);

        // Loop melalui setiap item yang dikirim dan masukkan ke database
        foreach ($data['items'] as $item) {
            DB::statement(
                'CALL sp_add_item(?,?,?)',
                [$data['transaksi_id'], $item['menu_id'], $item['qty']]
            );
        }

        return response()->json(['ok' => true]);
    }

    /**
     * UI waiter: Menampilkan daftar semua meja untuk memulai order.
     */
    public function orderIndex()
    {
        $mejas = Meja::orderBy('kode')->get();
        return view('order.index', compact('mejas'));
    }

    /**
     * UI waiter: Halaman Point of Sale (POS) untuk satu meja.
     */
    public function orderPOS(Meja $meja)
    {
        try {
            // Opsional: cegah open saat meja reserved, beri pesan lebih cepat di UI
            if ($meja->status === 'reserved') {
                return redirect()
                    ->route('order.index')
                    ->withErrors(['meja' => "Meja {$meja->kode} sedang di-reserve."]);
            }

            // Panggil SP untuk mendapatkan / membuat draft transaksi
            DB::statement('CALL sp_open_transaksi(?,?,@out_id)', [$meja->id, Auth::id()]);
            $row = DB::selectOne('SELECT @out_id AS id');

            if (!$row || !$row->id) {
                return redirect()
                    ->route('order.index')
                    ->withErrors(['transaksi' => 'Gagal membuka transaksi untuk meja ini.']);
            }

            $transaksiId = (int) $row->id;

            // Ambil data yang diperlukan untuk view
            $menus = Menu::where('aktif', true)->orderBy('nama')->get(['id', 'nama', 'harga']);
            $items = DB::table('pesanans as p')
                ->join('menus as mn', 'mn.id', '=', 'p.menu_id')
                ->where('p.transaksi_id', $transaksiId)
                ->orderBy('p.id')
                ->select('p.id', 'mn.nama', 'p.jumlah', 'p.harga_satuan', 'p.subtotal')
                ->get();
            $total = (int) DB::table('transaksis')->where('id', $transaksiId)->value('total') ?: 0;

            return view('order.pos', compact('meja', 'menus', 'items', 'total', 'transaksiId'));
        } catch (QueryException $e) {
            // Tangani error database (misal: ENUM tidak cocok) dengan mengarahkan kembali
            report($e);
            return redirect()
                ->route('order.index')
                ->withErrors(['db' => 'Order gagal dibuat. Cek skema database kolom status (ENUM).']);
        }
    }

    /**
     * UI kasir: Menampilkan daftar transaksi yang masih 'draft' dan siap dibayar.
     */
    // Di dalam TransaksiController.php
    // Di dalam TransaksiController.php

    public function kasirIndex()
    {
        // Tugas metode ini HANYA mengambil daftar transaksi yang menunggu pembayaran.
        $drafts = DB::table('transaksis as t')
            ->join('mejas as m', 'm.id', '=', 't.meja_id')
            ->where('t.status', 'draft')
            ->select('t.id', 'm.kode as meja', 't.total', 't.created_at')
            ->orderByDesc('t.id')
            ->paginate(12);

        // Kirim HANYA data $drafts ke view.
        return view('kasir.index', compact('drafts'));
    }


    /**
     * UI kasir: Halaman detail dan pembayaran untuk satu transaksi.
     */
    public function kasirBayar(Transaksi $transaksi)
    {
        // Pastikan hanya transaksi 'draft' yang bisa diakses
        abort_unless($transaksi->status === 'draft', 404);

        $meja = Meja::find($transaksi->meja_id);
        $items = DB::table('pesanans as p')
            ->join('menus as mn', 'mn.id', '=', 'p.menu_id')
            ->where('p.transaksi_id', $transaksi->id)
            ->select('mn.nama', 'p.jumlah', 'p.harga_satuan', 'p.subtotal')
            ->get();

        return view('kasir.bayar', [
            'transaksi' => $transaksi,
            'meja'      => $meja,
            'items'     => $items,
            'kembali'   => 0
        ]);
    }

    /**
     * Laporan harian (untuk kasir).
     */
    public function laporanHarian(Request $request)
    {
        $tanggal = $request->validate(['tanggal' => ['nullable', 'date']])['tanggal'] ?? now()->toDateString();

        $rows = DB::table('transaksis')
            ->whereDate('created_at', $tanggal)
            ->where('status', 'bayar') // Menggunakan status 'bayar' yang sudah konsisten
            ->get();

        return view('laporan.harian', compact('rows', 'tanggal'));
    }

    /**
     * Laporan rekapitulasi (untuk owner/administrator).
     */
    public function rekap()
    {
        $rows = DB::table('transaksis')
            ->selectRaw('DATE(created_at) tgl, COUNT(*) trx, SUM(total) omset')
            ->where('status', 'bayar') // Menggunakan status 'bayar' yang sudah konsisten
            ->groupBy('tgl')
            ->orderByDesc('tgl')
            ->paginate(30);

        return view('laporan.rekap', compact('rows'));
    }
    public function exportLaporanHarian(Request $request)
    {
        $tanggal = $request->input('tanggal', today()->toDateString());
        $fileName = 'laporan-harian-' . $tanggal . '.xlsx';

        return Excel::download(new LaporanHarianExport($tanggal), $fileName);
    }
    // Di TransaksiController.php
    public function printStruk(Transaksi $transaksi)
    {
        // Pastikan hanya transaksi yang sudah dibayar yang bisa dicetak
        abort_unless($transaksi->status === 'bayar', 404);

        $items = DB::table('pesanans as p')
            ->join('menus as mn', 'mn.id', '=', 'p.menu_id')
            ->where('p.transaksi_id', $transaksi->id)
            ->select('mn.nama', 'p.jumlah', 'p.harga_satuan', 'p.subtotal')
            ->get();

        return view('kasir.struk', compact('transaksi', 'items'));
    }
    public function pdfLaporanHarian(Request $request)
    {
        // 1. Ambil tanggal dari request, jika tidak ada, gunakan hari ini
        $tanggal = $request->input('tanggal', today()->toDateString());

        // 2. Ambil data yang sama persis seperti di halaman laporan harian
        $rows = DB::table('transaksis')
            ->whereDate('created_at', $tanggal)
            ->where('status', 'bayar')
            ->get();

        // 3. Muat view PDF dengan data tersebut
        $pdf = PDF::loadView('laporan.harian_pdf', compact('rows', 'tanggal'));

        // 4. Atur nama file dan mulai download
        $fileName = 'laporan-harian-' . $tanggal . '.pdf';
        return $pdf->download($fileName);
    }
    public function pdfRekap()
    {
        // 1. Ambil data yang sama persis seperti di halaman rekap
        $rows = DB::table('transaksis')
            ->selectRaw('DATE(created_at) tgl, COUNT(*) trx, SUM(total) omset')
            ->where('status', 'bayar')
            ->groupBy('tgl')
            ->orderByDesc('tgl')
            ->get(); // Gunakan get() bukan paginate() untuk PDF

        // 2. Muat view PDF dengan data tersebut
        $pdf = PDF::loadView('laporan.rekap_pdf', compact('rows'));

        // 3. Atur nama file dan mulai download
        $fileName = 'laporan-rekap-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($fileName);
    }
}
