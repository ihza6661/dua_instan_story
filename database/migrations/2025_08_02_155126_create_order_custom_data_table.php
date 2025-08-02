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
        Schema::create('order_custom_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->unique()->constrained()->onDelete('cascade');
            $table->json('form_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_custom_data');
    }
};
