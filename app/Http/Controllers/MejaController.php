<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    public function index()
    {
        $q = request('q');
        $status = request('status');

        $mejas = \App\Models\Meja::query()
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
            'status' => ['required', 'in:kosong,terpakai,reserved'],
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
            'status' => ['required', 'in:kosong,terpakai,reserved'],
        ]);
        $meja->update($data);
        return redirect()->route('meja.index')->with('ok', 'Meja diperbarui');
    }

    public function destroy(Meja $meja)
    {
        $meja->delete();
        return back()->with('ok', 'Meja dihapus');
    }
}
