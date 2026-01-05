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
        Schema::table('menus', function (Blueprint $table) {
            // Hapus kolom lama jika ada
            if (Schema::hasColumn('menus', 'category')) {
                $table->dropColumn('category');
            }
            
            // Tambah FK ID baru
            if (!Schema::hasColumn('menus', 'id_category')) {
                $table->unsignedBigInteger('id_category')->after('id_tenant')->nullable(); // Nullable untuk data yang sudah ada
                $table->foreign('id_category')->references('id_category')->on('categories')->onDelete('set null');
            }

            // Rename atau tambah is_available
            if (Schema::hasColumn('menus', 'status_available') && !Schema::hasColumn('menus', 'is_available')) {
                $table->renameColumn('status_available', 'is_available');
            } elseif (!Schema::hasColumn('menus', 'is_available')) {
                $table->boolean('is_available')->default(true)->after('stock');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            if (Schema::hasColumn('menus', 'id_category')) {
                $table->dropForeign(['id_category']);
                $table->dropColumn('id_category');
            }
            if (!Schema::hasColumn('menus', 'category')) {
                $table->string('category')->nullable();
            }
            if (Schema::hasColumn('menus', 'is_available')) {
                $table->renameColumn('is_available', 'status_available');
            }
        });
    }
};
