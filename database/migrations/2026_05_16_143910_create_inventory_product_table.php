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
    Schema::create('inventory_product', function (Blueprint $table) {
        $table->id();
        $table->foreignId('inventory_id')->constrained('inventories')->onDelete('cascade');
        $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
        $table->integer('quantity_used_per_order')->default(1);
        $table->timestamps();

        $table->unique(['inventory_id', 'product_id']);
    });
}

public function down(): void
{
    Schema::dropIfExists('inventory_product');
}
};
