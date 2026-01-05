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
        // 0. Tenants (diperlukan untuk konsistensi FK)
        Schema::create('tenants', function (Blueprint $table) {
            $table->id('id_tenant');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 1. Menus
        Schema::create('menus', function (Blueprint $table) {
            $table->id('id_menu');
            $table->unsignedBigInteger('id_tenant');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->integer('stock');
            $table->string('image')->nullable();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status_available')->default(true);
            $table->timestamps();

            $table->foreign('id_tenant')->references('id_tenant')->on('tenants')->onDelete('cascade');
        });

        // 2. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_order');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_tenant');
            $table->string('transaction_group');
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'cooking', 'ready', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade'); 
            $table->foreign('id_tenant')->references('id_tenant')->on('tenants')->onDelete('cascade');
        });

        // 3. Order Details
        Schema::create('order_details', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_order');
            $table->unsignedBigInteger('id_menu');
            $table->integer('qty');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();

            $table->foreign('id_order')->references('id_order')->on('orders')->onDelete('cascade');
            $table->foreign('id_menu')->references('id_menu')->on('menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('tenants');
    }
};
