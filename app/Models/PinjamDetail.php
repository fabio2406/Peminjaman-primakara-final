<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'pinjam_id', 'item_id', 'qty'
    ];

    // Relasi ke tabel pinjam
    public function pinjam()
    {
        return $this->belongsTo(Pinjam::class);
    }

    // Relasi ke tabel item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
