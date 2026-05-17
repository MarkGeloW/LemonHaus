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
    Schema::table('inventories', function (Blueprint $table) {
        $table->foreignId('product_id')
            ->nullable()
            ->after('id')
            ->constrained('products')
            ->nullOnDelete();

        $table->integer('quantity_used_per_order')
            ->default(1)
            ->after('stock_level');
    });
}

public function down(): void
{
    Schema::table('inventories', function (Blueprint $table) {
        $table->dropForeign(['product_id']);
        $table->dropColumn(['product_id', 'quantity_used_per_order']);
    });
}
};
