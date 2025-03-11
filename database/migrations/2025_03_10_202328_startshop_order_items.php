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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedSmallInteger('quantity');
            $table->decimal('price',6,2);
            $table->decimal('discount',4,2)->default(0);
            $table->timestamps();

            $table->unique(['order_id','product_id']);

            $table->foreign('order_id')->references('id')
                ->on('user')
                ->cascadeOnUpdate();

            $table->foreign('product_id')->references('id')
                ->on('products')
                ->cascadeOnUpdate();
        });;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
