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
        Schema::create('repair_reports', function (Blueprint $table) {
    $table->ulid('id')->primary();
    $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
    $table->foreignUlid('car_id')->constrained('cars')->restrictOnDelete();
    $table->foreignUlid('repair_order_id')->constrained('repair_orders')->restrictOnDelete();
    $table->foreignUlid('reported_by')->constrained('users')->restrictOnDelete();
    // Quality هو اللي بيعمله دايماً
    $table->string('report_number'); // RPR-2025-001
    $table->enum('result', ['repaired', 'partially_repaired', 'not_repaired']);
    $table->text('notes')->nullable();
    $table->timestamp('inspected_at');
    $table->timestamps();

    $table->unique(['tenant_id', 'report_number']);
    $table->index(['tenant_id', 'car_id']);
    $table->index(['repair_order_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_reports');
    }
};
