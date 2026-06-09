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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->integer('id_pemesanan')->autoIncrement();
            $table->string('booking_code', 50)->unique();
            $table->integer('id_customer');
            $table->integer('id_jadwal');
            $table->dateTime('tgl_pemesanan');
            $table->integer('jumlah_peserta');
            $table->decimal('total_harga', 10, 2);
            $table->enum('status_pembayaran', ['PENDING', 'WAITING_VERIFICATION', 'CONFIRMED', 'CANCELLED'])->default('PENDING');
            $table->enum('attendance_status', ['belum_hadir', 'hadir', 'absen'])->default('belum_hadir');
            $table->timestamps();

            $table->foreign('id_customer')->references('id_customer')->on('customers')->onDelete('cascade');
            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal_trip')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
