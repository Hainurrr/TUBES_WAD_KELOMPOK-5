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
        Schema::table('tenants', function (Blueprint $table) {
            // Cek apakah kolom sudah ada
            if (!Schema::hasColumn('tenants', 'id_user')) {
                // Nullable karena tabel sudah ada data sebelumnya
                $table->foreignId('id_user')->after('id_tenant')->nullable()->constrained('users', 'id_user')->onDelete('cascade');
            }
            if (!Schema::hasColumn('tenants', 'owner_name')) {
                $table->string('owner_name')->after('name');
            }
            if (!Schema::hasColumn('tenants', 'phone')) {
                $table->string('phone')->after('owner_name');
            }
            if (!Schema::hasColumn('tenants', 'address')) {
                $table->text('address')->after('phone');
            }
            if (!Schema::hasColumn('tenants', 'city')) {
                $table->string('city')->after('address');
            }
            if (!Schema::hasColumn('tenants', 'banner_image')) {
                $table->string('banner_image')->nullable()->after('city');
            }
            if (!Schema::hasColumn('tenants', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('banner_image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->dropColumn(['id_user', 'owner_name', 'phone', 'address', 'city', 'banner_image', 'is_active']);
        });
    }
};
