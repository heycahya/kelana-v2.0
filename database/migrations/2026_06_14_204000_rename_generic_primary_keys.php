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
        Schema::table('paket_wisata_galleries', function (Blueprint $table) {
            $table->renameColumn('id', 'id_gallery');
        });

        Schema::table('pemesanan_addon', function (Blueprint $table) {
            $table->renameColumn('id', 'id_pemesanan_addon');
        });

        Schema::table('wishlists', function (Blueprint $table) {
            $table->renameColumn('id', 'id_wishlist');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket_wisata_galleries', function (Blueprint $table) {
            $table->renameColumn('id_gallery', 'id');
        });

        Schema::table('pemesanan_addon', function (Blueprint $table) {
            $table->renameColumn('id_pemesanan_addon', 'id');
        });

        Schema::table('wishlists', function (Blueprint $table) {
            $table->renameColumn('id_wishlist', 'id');
        });
    }
};
