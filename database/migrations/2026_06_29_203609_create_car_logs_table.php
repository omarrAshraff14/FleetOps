<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_logs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
            $table->foreignUlid('request_id')
                  ->constrained('requests')
                  ->cascadeOnDelete();
            $table->foreignUlid('car_id')
                  ->constrained('cars')
                  ->restrictOnDelete();
            $table->foreignUlid('driver_id')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->foreignUlid('rep_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // بيانات الخروج
            $table->foreignUlid('departure_branch_id')
                  ->constrained('branches')
                  ->restrictOnDelete();
            $table->unsignedInteger('departure_km');
            $table->enum('departure_fuel_level', [
                'empty',
                'quarter',
                'half',
                'three_quarters',
                'full'
            ]);
            $table->decimal('departure_fuel_amount', 8, 2)->nullable();
            // قيمة البنزين بالجنيه
            $table->timestamp('departure_at');
            $table->foreignUlid('departure_approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // بيانات الوجهة
            $table->string('destination_address')->nullable();
            $table->string('customer_address')->nullable();

            // بيانات الرجوع
            $table->foreignUlid('return_branch_id')
                  ->nullable()
                  ->constrained('branches')
                  ->nullOnDelete();
            $table->unsignedInteger('return_km')->nullable();
            $table->enum('return_fuel_level', [
                'empty',
                'quarter',
                'half',
                'three_quarters',
                'full'
            ])->nullable();
            $table->timestamp('return_at')->nullable();
            $table->foreignUlid('return_approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // حسابات
            $table->unsignedInteger('total_km')
                  ->nullable();
            // return_km - departure_km
            $table->unsignedInteger('total_minutes')
                  ->nullable();
            // الوقت الكلي للرحلة

            // مصاريف
            $table->decimal('travel_allowance', 10, 2)
                  ->default(0);
            // بدل سفر
            $table->decimal('other_expenses', 10, 2)
                  ->default(0);
            $table->string('other_expenses_note')->nullable();

            $table->decimal('daily_km_limit', 8, 2)->nullable();
            // كم مسموح يومي (للإيجار)
            // $table->decimal('actual_km_consumed', 10, 2)->nullable();
            // كم العودة > عدد الكيلومتر المستهلك (computed)
            $table->decimal('extra_km_charge', 10, 2)->nullable();
            // تكلفة الكيلومتر الزايد
            $table->decimal('daily_rate', 10, 2)->nullable();
            // السعر اليومي (للإيجار)
            $table->unsignedInteger('rental_days')->nullable();
            $table->string('route_details')->nullable();
            // edit rep kroky after recieve car from customer
            $table->boolean('customer_handback_done')->default(false);
            $table->timestamp('customer_handback_at')->nullable();

            // حالة اللوج
            $table->enum('status', [
                'active',
                'completed',
                'cancelled'
            ])->default('active');

            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['tenant_id', 'car_id']);
            $table->index(['tenant_id', 'request_id']);
            $table->index(['tenant_id', 'driver_id']);
            $table->index(['tenant_id', 'status']);
            $table->index(['departure_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_logs');
    }
};
