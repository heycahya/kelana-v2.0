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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->integer('id_pembayaran')->autoIncrement();
            $table->integer('id_pemesanan');
            $table->string('transaction_id', 255)->nullable();
            $table->string('snap_token', 255)->nullable();
            $table->dateTime('tgl_pembayaran')->nullable();
            $table->decimal('jumlah_bayar', 10, 2);
            $table->string('metode_pembayaran', 100)->nullable();
            $table->string('bukti_pembayaran', 255)->nullable();
            $table->string('status_transaksi', 50)->nullable();
            $table->timestamps();

            $table->foreign('id_pemesanan')->references('id_pemesanan')->on('pemesanan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
