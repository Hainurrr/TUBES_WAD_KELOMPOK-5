<?php

// MANPROSI_TELYUCAN_IMPROVEMENT - Migration untuk integrasi Midtrans Payment Gateway

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MANPROSI_TELYUCAN_IMPROVEMENT
     * Menambahkan kolom-kolom yang diperlukan untuk integrasi Midtrans Snap API.
     * - snap_token: menyimpan token dari Midtrans Snap untuk popup pembayaran
     * - midtrans_transaction_id: ID unik transaksi di sisi Midtrans (order_id)
     * - payment_status: status pembayaran terpisah dari status proses pesanan
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('status');
            $table->string('midtrans_transaction_id')->nullable()->after('snap_token');
            $table->string('payment_status')->default('unpaid')->after('midtrans_transaction_id');
            // payment_status values: 'unpaid', 'paid', 'expired', 'cancelled'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'midtrans_transaction_id', 'payment_status']);
        });
    }
};
