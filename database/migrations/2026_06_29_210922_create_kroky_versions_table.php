<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kroky_versions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
            $table->foreignUlid('car_id')
                  ->constrained('cars')
                  ->cascadeOnDelete();
            $table->foreignUlid('car_log_id')
                  ->nullable()
                  ->constrained('car_logs')
                  ->nullOnDelete();
            // null = الـ version الأولى عند إضافة العربية
            $table->unsignedSmallInteger('version_number');
            $table->enum('type', [
                'initial',      // أول إضافة للعربية
                'departure',    // snapshot عند الخروج
                'return',       // snapshot عند الرجوع
                'post_repair'   // بعد الإصلاح
            ]);
            $table->foreignUlid('created_by')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->text('notes')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->timestamp('locked_at')->nullable();
            $table->timestamps();

            $table->unique(['car_id', 'version_number']);
            $table->index(['tenant_id', 'car_id']);
            $table->index(['car_log_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kroky_versions');
    }
};
