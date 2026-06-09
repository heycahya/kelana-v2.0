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
        Schema::create('jadwal_trip', function (Blueprint $table) {
            $table->integer('id_jadwal')->autoIncrement();
            $table->integer('id_paket');
            $table->integer('id_leader');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('kuota');
            $table->enum('status_trip', ['Draft', 'Open', 'Berjalan', 'Selesai', 'Batal']);
            $table->timestamps();

            $table->foreign('id_paket')->references('id_paket')->on('paket_wisata')->onDelete('cascade');
            $table->foreign('id_leader')->references('id_leader')->on('trip_leaders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_trip');
    }
};
