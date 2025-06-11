<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pinjam_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinjam_id')->constrained('pinjams')->onDelete('cascade'); // Relasi ke tabel pinjams
            $table->foreignId('item_id')->constrained('items')->onDelete('restrict'); // Relasi ke tabel items
            $table->integer('qty'); // Jumlah barang yang dipinjam
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjams_details');
    }
};
