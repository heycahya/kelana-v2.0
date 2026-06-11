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
        Schema::create('customers', function (Blueprint $table) {
            $table->integer('id_customer')->autoIncrement();
            $table->string('nama_customer', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->string('no_telp', 15)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
