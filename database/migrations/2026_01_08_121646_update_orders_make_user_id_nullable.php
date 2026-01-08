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
        Schema::table('orders', function (Blueprint $table) {
            // Ubah id_user menjadi nullable
            $table->unsignedBigInteger('id_user')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kembalikan menjadi tidak nullable (warning: bisa error jika ada data null)
            $table->unsignedBigInteger('id_user')->nullable(false)->change();
        });
    }
};
