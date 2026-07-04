<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kroky_points', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
            $table->foreignUlid('kroky_version_id')
                  ->constrained('kroky_versions')
                  ->cascadeOnDelete();

            $table->unsignedTinyInteger('point_number');
            // الرقم الظاهر على الصورة

            $table->enum('view', [
                'top',
                'side_right',
                'side_left',
                'front',
                'rear'
            ]);

            // موقع النقطة كـ percentage
            $table->decimal('x_percent', 5, 2);
            $table->decimal('y_percent', 5, 2);

            $table->enum('damage_type', [
                'scratch',       // خدش
                'dent',          // حفرة/طبشة
                'crack',         // شرخ
                'missing_part',  // قطعة ناقصة
                'other'
            ]);

            $table->enum('severity', [
                'minor',    // بسيط
                'moderate', // متوسط
                'severe'    // شديد
            ]);

            $table->enum('status', [
                'existing',  // تلف قديم موثق
                'new',       // تلف جديد اكتشف
                'repaired'   // تم إصلاحه
            ]);

            $table->text('description')->nullable();

            // مرتبط بتقرير تلفيات لو status = new
            $table->foreignUlid('damage_report_id')
                  ->nullable()
                  ->constrained('damage_reports')
                  ->nullOnDelete();

            // مرتبط بأمر إصلاح لو status = repaired
           $table->foreignUlid('repair_report_id')
      ->nullable()
      ->constrained('repair_reports')
      ->nullOnDelete();

            $table->timestamps();

            $table->index(['tenant_id', 'kroky_version_id']);
            $table->index(['kroky_version_id', 'view']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kroky_points');
    }
};
