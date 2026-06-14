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
        Schema::table('paket_wisata', function (Blueprint $table) {
            $table->decimal('harga', 15, 2)->change();
        });

        Schema::table('pemesanan', function (Blueprint $table) {
            $table->decimal('total_harga', 15, 2)->change();
            $table->decimal('diskon', 15, 2)->change();
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->decimal('jumlah_bayar', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket_wisata', function (Blueprint $table) {
            $table->decimal('harga', 10, 2)->change();
        });

        Schema::table('pemesanan', function (Blueprint $table) {
            $table->decimal('total_harga', 10, 2)->change();
            $table->decimal('diskon', 10, 2)->change();
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->decimal('jumlah_bayar', 10, 2)->change();
        });
    }
};
