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
    Schema::create('order_records', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('order_id')->nullable();
        $table->string('customer_name');
        $table->string('product_name');
        $table->integer('quantity');
        $table->decimal('price', 10, 2);
        $table->decimal('total', 10, 2);
        $table->string('status')->default('completed');
        $table->timestamp('completed_at')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('order_records');
}
};
