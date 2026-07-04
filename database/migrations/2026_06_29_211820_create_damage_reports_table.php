<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
            $table->foreignUlid('car_log_id')
                  ->constrained('car_logs')
                  ->cascadeOnDelete();
            $table->foreignUlid('car_id')
                  ->constrained('cars')
                  ->restrictOnDelete();
            $table->foreignUlid('reported_by')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->string('report_number');
            // DMG-2025-001
            $table->enum('type', [
                'driver_report',   // المندوب بلّغ
                'quality_report'   // الكواليتي اكتشف
            ]);
            $table->text('description');
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->enum('status', [
                'pending',
                'under_repair',
                'repaired',
                'closed'
            ])->default('pending');
            $table->timestamps();

            $table->unique(['tenant_id', 'report_number']);
            $table->index(['tenant_id', 'car_id']);
            $table->index(['tenant_id', 'car_log_id']);
            $table->index(['tenant_id', 'status']);
        });

        Schema::create('repair_orders', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
            $table->foreignUlid('car_id')
                  ->constrained('cars')
                  ->restrictOnDelete();
            $table->foreignUlid('damage_report_id')
                  ->nullable()
                  ->constrained('damage_reports')
                  ->nullOnDelete();
            $table->string('order_number');
            // REP-2025-001
            $table->string('supplier_name');
            // اسم الورشة
            $table->string('supplier_contact')->nullable();
            $table->enum('status', [
                'pending',
                'in_progress',
                'completed',
                'cancelled'
            ])->default('pending');
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignUlid('created_by')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->foreignUlid('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'order_number']);
            $table->index(['tenant_id', 'car_id']);
            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repair_orders');
        Schema::dropIfExists('damage_reports');
    }
};
