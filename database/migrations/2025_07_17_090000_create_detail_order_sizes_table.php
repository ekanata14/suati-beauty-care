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
        Schema::create('detail_order_sizes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_detail_order');
            $table->foreign('id_detail_order')->references('id')->on('detail_orders')->onDelete('cascade');
            $table->string('size');
            $table->integer('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_order_sizes');
    }
};
