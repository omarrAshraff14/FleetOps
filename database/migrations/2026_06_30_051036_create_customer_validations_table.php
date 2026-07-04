<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_validations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
            $table->foreignUlid('car_log_id')
                  ->constrained('car_logs')
                  ->cascadeOnDelete();
            $table->foreignUlid('customer_id')
                  ->constrained('customers')
                  ->restrictOnDelete();
            $table->foreignUlid('kroky_version_id')
                  ->constrained('kroky_versions')
                  ->restrictOnDelete();
            $table->string('qr_token')->unique();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->text('signature_data')->nullable();
            // base64 للتوقيع الإلكتروني
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'car_log_id']);
            $table->index('qr_token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_validations');
    }
};
