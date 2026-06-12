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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('paket_wisata_id');
            $table->timestamps();
            $table->unique(['customer_id', 'paket_wisata_id']);

            $table->foreign('customer_id')->references('id_customer')->on('customers')->cascadeOnDelete();
            $table->foreign('paket_wisata_id')->references('id_paket')->on('paket_wisata')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
