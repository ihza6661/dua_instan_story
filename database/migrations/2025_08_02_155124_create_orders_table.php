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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users');
            $table->string('order_number', 50)->unique();
            $table->unsignedBigInteger('total_amount');
            $table->text('shipping_address')->nullable();
            $table->enum('order_status', [
                'pending_payment', 
                'processing', 
                'design_approval', 
                'in_production', 
                'shipped', 
                'completed', 
                'cancelled'
            ])->default('pending_payment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
