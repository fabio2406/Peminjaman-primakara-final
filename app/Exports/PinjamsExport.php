<?php

namespace App\Exports;

use App\Models\Pinjam;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class PinjamsExport implements FromArray, WithHeadings
{
    protected $month;
    protected $year;

    public function __construct($month = null, $year = null)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function array(): array
    {
        // Query data dengan filter bulan dan tahun
        $query = Pinjam::with(['user', 'details.item'])
            ->orderBy('loan_date');

        if ($this->month) {
            $query->whereMonth('loan_date', $this->month);
        }

        if ($this->year) {
            $query->whereYear('loan_date', $this->year);
        }

        $data = $query->get()->groupBy(function ($pinjam) {
            return Carbon::parse($pinjam->loan_date)->format('l, F j, Y');
        });

        $output = [];
        foreach ($data as $date => $records) {
            // Tambahkan header tanggal
            $output[] = [$date, '', '', '', '', '', ''];

            foreach ($records as $pinjam) {
                // Gabungkan nama item
                $items = $pinjam->details->map(function ($detail) {
                    return $detail->item->nama_item . ' (' . $detail->qty . ')';
                })->join(', ');

                // Tambahkan data pinjam
                $output[] = [
                    '', // Tanggal sudah diwakili oleh header
                    $pinjam->user->name ?? '',
                    $pinjam->instansi ?? '',
                    $items,
                    $pinjam->loan_date->format('H:i'),
                    $pinjam->return_date->format('H:i'),
                    $pinjam->keterangan_peminjam ?? '',
                ];
            }
        }

        return $output;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Username',
            'Instansi',
            'Item',
            'Jam Loan',
            'Jam Return',
            'Keterangan Peminjam',
        ];
    }
}

