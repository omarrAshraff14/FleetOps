<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repair_order_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
            $table->foreignUlid('repair_order_id')
                  ->constrained('repair_orders')
                  ->cascadeOnDelete();
            $table->string('description');
            $table->decimal('cost', 10, 2)->default(0);
            $table->timestamps();

            $table->index(['tenant_id', 'repair_order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repair_order_items');
    }
};
