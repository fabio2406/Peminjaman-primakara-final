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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict'); // Pengguna yang melakukan perubahan
            $table->string('activity_type'); // Tipe aktivitas, seperti 'create', 'update', 'delete'
            $table->unsignedBigInteger('pinjam_id'); // ID peminjaman yang diubah
            $table->text('changes')->nullable(); // Detil perubahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
