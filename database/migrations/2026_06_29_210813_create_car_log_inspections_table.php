<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_log_inspections', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
            $table->foreignUlid('car_log_id')
                  ->constrained('car_logs')
                  ->cascadeOnDelete();
            $table->foreignUlid('inspection_template_id')
                  ->constrained('inspection_templates')
                  ->restrictOnDelete();
            $table->enum('type', [
                'quality_departure',  // كواليتي قبل الخروج
                'driver_departure',   // مندوب قبل الخروج
                'driver_return',      // مندوب عند الرجوع
                'quality_return'      // كواليتي بعد الرجوع
            ]);
            $table->foreignUlid('inspected_by')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->enum('overall_result', [
                'pass',
                'fail',
                'pass_with_notes'
            ]);
            $table->text('notes')->nullable();
            $table->timestamp('inspected_at');
            $table->timestamps();

            $table->index(['tenant_id', 'car_log_id']);
            $table->index(['car_log_id', 'type']);
        });

        Schema::create('car_log_inspection_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
            $table->foreignUlid('car_log_inspection_id')
                  ->constrained('car_log_inspections')
                  ->cascadeOnDelete();
            $table->foreignUlid('inspection_template_item_id')
                  ->constrained('inspection_template_items')
                  ->restrictOnDelete();
            $table->enum('result', ['pass', 'fail', 'na']);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'car_log_inspection_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_log_inspection_items');
        Schema::dropIfExists('car_log_inspections');
    }
};
