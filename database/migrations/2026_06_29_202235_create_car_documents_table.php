<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_documents', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
            $table->foreignUlid('car_id')
                  ->constrained('cars')
                  ->cascadeOnDelete();
            $table->enum('type', [
                'license',       // رخصة تسيير
                'insurance',     // تأمين
                'inspection',    // فحص دوري
                'other'
            ]);
            $table->string('file_path');
            $table->date('expiry_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'car_id']);
            $table->index(['expiry_date']);
            // مهم لـ alert انتهاء الوثائق
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_documents');
    }
};
