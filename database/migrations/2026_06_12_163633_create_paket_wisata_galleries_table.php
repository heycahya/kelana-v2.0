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
        Schema::create('paket_wisata_galleries', function (Blueprint $table) {
            $table->id();
            $table->integer('paket_wisata_id');
            $table->string('image_url');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->foreign('paket_wisata_id')->references('id_paket')->on('paket_wisata')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_wisata_galleries');
    }
};
