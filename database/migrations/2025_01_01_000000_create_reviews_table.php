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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('id_review'); // PK
            
            // FK ke Users (Reviewer)
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');

            // FK ke Menus (Item yang diulas)
            $table->unsignedBigInteger('id_menu');
            $table->foreign('id_menu')->references('id_menu')->on('menus')->onDelete('cascade');

            // FK ke Orders (untuk verifikasi pembelian)
            $table->unsignedBigInteger('id_order'); 
            $table->foreign('id_order')->references('id_order')->on('orders')->onDelete('cascade');

            $table->integer('rating'); // 1-5
            $table->text('comment'); // Review text
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
