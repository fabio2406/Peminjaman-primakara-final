<?php

namespace App\Exports;

use App\Models\PinjamDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PinjamDetailsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = PinjamDetail::with(['pinjam.user', 'item.category']);
    
        // Apply User Filters
        if (!empty($this->filters['searchUserName'])) {
            $query->whereHas('pinjam.user', function ($q) {
                $q->where('name', 'like', '%' . $this->filters['searchUserName'] . '%');
            });
        }
        if (!empty($this->filters['searchUserUsername'])) {
            $query->whereHas('pinjam.user', function ($q) {
                $q->where('username', 'like', '%' . $this->filters['searchUserUsername'] . '%');
            });
        }
        if (!empty($this->filters['searchUserPhone'])) {
            $query->whereHas('pinjam.user', function ($q) {
                $q->where('phone', 'like', '%' . $this->filters['searchUserPhone'] . '%');
            });
        }
        if (!empty($this->filters['roleFilter']) && $this->filters['roleFilter'] !== 'all') {
            $query->whereHas('pinjam.user', function ($q) {
                $q->where('role', $this->filters['roleFilter']);
            });
        }
    
        // Apply Item Filters
        if (!empty($this->filters['searchItemCode'])) {
            $query->whereHas('item', function ($q) {
                $q->where('kode_item', 'like', '%' . $this->filters['searchItemCode'] . '%');
            });
        }
        if (!empty($this->filters['searchItemName'])) {
            $query->whereHas('item', function ($q) {
                $q->where('nama_item', 'like', '%' . $this->filters['searchItemName'] . '%');
            });
        }
        if (!empty($this->filters['category']) && $this->filters['category'] !== 'all') {
            $query->whereHas('item.category', function ($q) {
                $q->where('id', $this->filters['category']);
            });
        }
    
        // Apply Date Filters
        if (!empty($this->filters['loan_date_start']) && !empty($this->filters['loan_date_end'])) {
            $query->whereHas('pinjam', function ($q) {
                $q->whereBetween('loan_date', [$this->filters['loan_date_start'], $this->filters['loan_date_end']]);
            });
        }
        if (!empty($this->filters['return_date_start']) && !empty($this->filters['return_date_end'])) {
            $query->whereHas('pinjam', function ($q) {
                $q->whereBetween('return_date', [$this->filters['return_date_start'], $this->filters['return_date_end']]);
            });
        }
    
        // Apply Status Filter
        if (!empty($this->filters['status_filter']) && $this->filters['status_filter'] !== 'all') {
            $query->whereHas('pinjam', function ($q) {
                $q->where('status', $this->filters['status_filter']);
            });
        }

         // Apply Status Filter
         if (!empty($this->filters['status_warek_filter']) && $this->filters['status_warek_filter'] !== 'all') {
            $query->whereHas('pinjam', function ($q) {
                $q->where('status_warek', $this->filters['status_warek_filter']);
            });
        }
    
        // Apply Additional Text Filters
        if (!empty($this->filters['searchKeteranganPeminjam'])) {
            $query->whereHas('pinjam', function ($q) {
                $q->where('keterangan_peminjam', 'like', '%' . $this->filters['searchKeteranganPeminjam'] . '%');
            });
        }
        if (!empty($this->filters['searchKeteranganPenyetuju'])) {
            $query->whereHas('pinjam', function ($q) {
                $q->where('keterangan_penyetuju', 'like', '%' . $this->filters['searchKeteranganPenyetuju'] . '%');
            });
        }
    
        // Get the filtered results
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'Nama', 'NIM', 'Phone', 'Role', 'Kode Item', 
            'Nama Item', 'Kategori', 'QTY', 'Tanggal Pinjam', 
            'Tanggal Pengembalian', 'Tanggal Pengembalian (asli)', 
            'Status', 'Keterangan Peminjam', 'Keterangan Penyetuju'
        ];
    }

    public function map($detail): array
    {
        return [
            $detail->pinjam->id,
            $detail->pinjam->user->name,
            $detail->pinjam->user->username,
            $detail->pinjam->user->phone,
            $detail->pinjam->user->role,
            $detail->item->kode_item,
            $detail->item->nama_item,
            $detail->item->category->name,
            $detail->qty,
            $detail->pinjam->loan_date,
            $detail->pinjam->return_date,
            $detail->pinjam->actual_return_date,
            ucfirst($detail->pinjam->status),
            $detail->pinjam->keterangan_peminjam,
            $detail->pinjam->keterangan_penyetuju,
        ];
    }
}
