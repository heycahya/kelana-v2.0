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
        // 1. Create chat_rooms table
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->integer('id_room')->autoIncrement();
            $table->string('booking_code', 50)->nullable();
            $table->string('nama_room', 150);
            $table->timestamps();
            
            // Add index or reference if appropriate, but keeping it simple as a string to reference booking codes
            $table->index('booking_code');
        });

        // 2. Create chat_participants table
        Schema::create('chat_participants', function (Blueprint $table) {
            $table->integer('id_participant')->autoIncrement();
            $table->integer('id_room');
            $table->enum('role_type', ['admin', 'customer', 'trip_leader']);
            $table->unsignedInteger('role_id');
            $table->timestamps();

            $table->foreign('id_room')->references('id_room')->on('chat_rooms')->cascadeOnDelete();
            $table->index(['role_type', 'role_id']);
        });

        // 3. Recreate messages table with room-based schema
        Schema::dropIfExists('messages');
        
        Schema::create('messages', function (Blueprint $table) {
            $table->integer('id_message')->autoIncrement();
            $table->integer('id_room');
            $table->enum('sender_role', ['admin', 'customer', 'trip_leader']);
            $table->unsignedInteger('sender_id');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('id_room')->references('id_room')->on('chat_rooms')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('chat_participants');
        Schema::dropIfExists('chat_rooms');

        // Restore original polymorphic messages table
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('sender_type');
            $table->unsignedInteger('sender_id');
            $table->string('receiver_type');
            $table->unsignedInteger('receiver_id');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }
};
