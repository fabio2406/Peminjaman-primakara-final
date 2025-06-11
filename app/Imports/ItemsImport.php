<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Models\Item;

class ItemsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Check if the item with the same kode_item exists
        $item = Item::where('kode_item', $row['kode_item'])->first();

        // If the item exists, update it; otherwise, create a new item
        if ($item) {
            $item->update([
                'nama_item'  => $row['nama_item'],
                'stok'       => $row['stok'],
                'category_id'=> $row['category_id'],
                'pengelola'  => $row['pengelola'],
            ]);
            return $item;
        } else {
            return new Item([
                'kode_item'  => $row['kode_item'],
                'nama_item'  => $row['nama_item'],
                'stok'       => $row['stok'],
                'category_id'=> $row['category_id'],
                'pengelola'  => $row['pengelola'],
            ]);
        }
    }

    public function rules(): array
    {
        return [
            '*.kode_item'   => 'required|string',
            '*.nama_item'   => 'required|string',
            '*.stok'        => 'required|integer|min:0',
            '*.category_id' => 'required|exists:categories,id',
            '*.pengelola'   => 'required|in:dpt,dala,sdm,warek',
        ];
    }

    public function headingRow(): int
    {
        return 1; // Header ada di baris pertama
    }
}
