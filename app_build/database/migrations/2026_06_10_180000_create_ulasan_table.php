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
        Schema::create('ulasan', function (Blueprint $table) {
            $table->integer('id_ulasan')->autoIncrement();
            $table->integer('id_customer');
            $table->integer('id_jadwal');
            $table->tinyInteger('rating');
            $table->text('komentar')->nullable();
            $table->timestamps();

            $table->foreign('id_customer')->references('id_customer')->on('customers')->onDelete('cascade');
            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal_trip')->onDelete('cascade');
            $table->unique(['id_customer', 'id_jadwal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
