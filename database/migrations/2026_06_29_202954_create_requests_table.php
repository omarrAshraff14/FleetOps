<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
            $table->foreignUlid('branch_id')
                  ->constrained('branches')
                  ->restrictOnDelete();
            $table->foreignUlid('request_type_id')
                  ->constrained('request_types')
                  ->restrictOnDelete();

            // رقم الطلب
            $table->string('request_number');
            // REQ-2025-0001
            $table->string('operation_order_number')->nullable();
            // رقم أمر التشغيل لو مختلف

            // الأطراف
            $table->foreignUlid('customer_id')
                  ->nullable()
                  ->constrained('customers')
                  ->nullOnDelete();
            // $table->foreignUlid('car_id') 
            //       ->nullable()
            //       ->constrained('cars')
            //       ->nullOnDelete();
            // $table->foreignUlid('driver_id')
            //       ->nullable()
            //       ->constrained('users')
            //       ->nullOnDelete();
            // $table->foreignUlid('rep_id')
            //       ->nullable()
            //       ->constrained('users')
            //       ->nullOnDelete();
            // // المندوب، nullable
            // $table->foreignUlid('companion_id')
            //       ->nullable()
            //       ->constrained('users')
            //       ->nullOnDelete();
            // مرافق، nullable

            // من أنشأ وعيّن
            $table->foreignUlid('created_by')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->foreignUlid('assigned_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // التوقيتات
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('expected_return_at')->nullable();

            // الحالة
            $table->enum('status', [
                'pending',        // في انتظار تعيين عربية
                'assigned',       // اتعينت عربية وسائق
                'quality_check',  // عند الكواليتي
                'dispatched',     // وافق الكواليتي، في انتظار المندوب
                'in_progress',    // خرجت العربية
                'returning',      // في الطريق للرجوع
                'completed',      // اكتملت
                'cancelled'       // اتلغت
            ])->default('pending');

            $table->text('cancel_reason')->nullable();

            // بيانات GPS
            $table->boolean('gps_tracking')->default(false);

            // الحقول الإضافية حسب نوع الطلب
            $table->json('custom_data')->nullable();
            // القيم الفعلية للـ custom_fields

            $table->text('notes')->nullable();
            $table->boolean('has_photo_proof')->default(false);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->unique(['tenant_id', 'request_number']);
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'customer_id']);
            $table->index(['scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
