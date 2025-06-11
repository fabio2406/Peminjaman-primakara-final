<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('kode_item')->unique();
            $table->string('nama_item');
            $table->integer('stok');
            $table->foreignId('category_id')->constrained()->onDelete('restrict'); // Kategori sebagai foreign key
            $table->enum('pengelola', ['dpt', 'dala','sdm','warek']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
}
