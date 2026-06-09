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
        Schema::create('trip_leaders', function (Blueprint $table) {
            $table->integer('id_leader')->autoIncrement();
            $table->string('nama_leader', 100);
            $table->string('no_telp', 15);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_leaders');
    }
};
