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
        Schema::create('stock_quantities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id');
            $table->string('sku');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('product_locations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_quantities');
    }
};
