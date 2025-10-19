<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MejaController extends Controller
{
    public function index()
    {
        $q = request('q');
        $status = request('status');

        $mejas = Meja::query()
            ->when($q, fn($qr) => $qr->where('kode', 'like', "%{$q}%"))
            ->when($status, fn($qr) => $qr->where('status', $status))
            ->orderBy('kode')
            ->paginate(12);

        return view('meja.index', compact('mejas'));
    }


    public function create()
    {
        return view('meja.form', ['meja' => new Meja]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:10', 'unique:mejas,kode'],
            // PERBAIKAN: Menambahkan 'tersedia' dan 'terpakai' ke dalam validasi
            'status' => ['required', 'in:kosong,tersedia,reserved,terpakai'],
        ]);
        Meja::create($data);
        return redirect()->route('meja.index')->with('ok', 'Meja dibuat');
    }

    public function edit(Meja $meja)
    {
        return view('meja.form', compact('meja'));
    }

    public function update(Request $request, Meja $meja)
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:10', 'unique:mejas,kode,' . $meja->id],
            // PERBAIKAN: Menambahkan 'terpakai' ke dalam validasi agar konsisten
            'status' => ['required', 'in:kosong,tersedia,reserved,terpakai'],
        ]);
        $meja->update($data);
        return redirect()->route('meja.index')->with('ok', 'Meja diperbarui');
    }

    public function destroy(Meja $meja)
    {
        // Cek apakah ada transaksi berstatus 'draft' yang menggunakan meja ini.
        $isUsed = DB::table('transaksis')
            ->where('meja_id', $meja->id)
            ->where('status', 'draft')
            ->exists();

        if ($isUsed) {
            // Jika ada, batalkan penghapusan dan beri pesan error.
            return back()->withErrors(['gagal' => 'Gagal menghapus! Meja sedang digunakan dalam transaksi aktif.']);
        }

        // Jika tidak ada, lanjutkan proses penghapusan.
        $meja->delete();
        return back()->with('ok', 'Meja berhasil dihapus.');
    }
}
