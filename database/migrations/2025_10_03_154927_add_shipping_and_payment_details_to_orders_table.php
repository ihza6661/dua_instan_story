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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('shipping_cost')->default(0)->after('total_amount');
            $table->string('shipping_service')->nullable()->after('shipping_cost');
            $table->string('courier')->nullable()->after('shipping_service');
            $table->string('snap_token', 255)->nullable()->after('courier');
            $table->string('payment_gateway', 100)->nullable()->after('snap_token');
            $table->string('payment_status', 100)->nullable()->after('payment_gateway');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_cost', 'shipping_service', 'courier', 'snap_token', 'payment_gateway', 'payment_status']);
        });
    }
};
