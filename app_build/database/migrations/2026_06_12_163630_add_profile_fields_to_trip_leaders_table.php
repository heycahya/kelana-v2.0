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
        Schema::table('trip_leaders', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('no_telp');
            $table->text('bio')->nullable()->after('avatar');
            $table->decimal('rating_akumulatif', 2, 1)->default(5.0)->after('bio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_leaders', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'bio', 'rating_akumulatif']);
        });
    }
};
