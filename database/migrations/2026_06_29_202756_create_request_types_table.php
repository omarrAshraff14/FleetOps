<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_types', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
            $table->string('name');
            // تأجير، مشوار داخلي، شحن، إلخ
            $table->string('slug');
            // rental, internal_trip, shipping
            $table->boolean('requires_customer')->default(false);
            $table->boolean('requires_return')->default(true);
            $table->json('custom_fields')->nullable();
            // template الحقول الإضافية
            // [
            //   {"key": "contract_number", "label": "رقم العقد", "type": "text", "required": true},
            //   {"key": "daily_rate", "label": "السعر اليومي", "type": "number", "required": false}
            // ]
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['tenant_id', 'slug']);
            $table->index('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_types');
    }
};
