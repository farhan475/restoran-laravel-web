<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanHarianExport implements FromCollection, WithHeadings, WithMapping
{
    protected $tanggal;

    public function __construct(string $tanggal)
    {
        $this->tanggal = $tanggal;
    }

    // Menentukan header kolom
    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Meja',
            'Waiter',
            'Total Tagihan',
            'Jumlah Dibayar',
            'Waktu Transaksi',
        ];
    }

    // Mengambil data dari database
    public function collection()
    {
        return DB::table('transaksis as t')
            ->join('mejas as m', 't.meja_id', '=', 'm.id')
            ->join('users as u', 't.waiter_id', '=', 'u.id')
            ->where('t.status', 'bayar')
            ->whereDate('t.created_at', $this->tanggal)
            ->select('t.id', 'm.kode as meja', 'u.name as waiter', 't.total', 't.dibayar', 't.created_at')
            ->get();
    }

    // Memetakan setiap baris data ke format yang diinginkan
    public function map($row): array
    {
        return [
            $row->id,
            $row->meja,
            $row->waiter,
            $row->total,
            $row->dibayar,
            $row->created_at,
        ];
    }
}