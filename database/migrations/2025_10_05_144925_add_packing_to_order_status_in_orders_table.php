<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN order_status ENUM('pending_payment', 'processing', 'packing', 'design_approval', 'in_production', 'shipped', 'completed', 'cancelled') NOT NULL DEFAULT 'pending_payment'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN order_status ENUM('pending_payment', 'processing', 'design_approval', 'in_production', 'shipped', 'completed', 'cancelled') NOT NULL DEFAULT 'pending_payment'");
    }
};