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
        Schema::create('pemesanan_addon', function (Blueprint $table) {
            $table->id();
            $table->integer('pemesanan_id');
            $table->foreignId('add_on_id')->constrained('add_ons')->cascadeOnDelete();
            $table->integer('kuantitas')->default(1);
            $table->integer('subtotal');
            $table->timestamps();

            $table->foreign('pemesanan_id')->references('id_pemesanan')->on('pemesanan')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan_addon');
    }
};
