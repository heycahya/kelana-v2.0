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
        Schema::create('paket_wisata', function (Blueprint $table) {
            $table->integer('id_paket')->autoIncrement();
            $table->string('nama_paket', 150);
            $table->text('deskripsi');
            $table->decimal('harga', 10, 2);
            $table->text('rute');
            $table->text('fasilitas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_wisata');
    }
};
