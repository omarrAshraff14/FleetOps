<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspection_templates', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', [
                'departure',
                'return',
                'maintenance'
            ]);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['tenant_id', 'type']);
        });

        Schema::create('inspection_template_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
            $table->foreignUlid('inspection_template_id')
                  ->constrained('inspection_templates')
                  ->cascadeOnDelete();
            $table->string('category');
            // "إطارات"، "محرك"، "هيكل"
            $table->string('item_name');
            // "ضغط الإطارات"، "مستوى الزيت"
            $table->boolean('is_required')->default(true);
            $table->unsignedTinyInteger('order_index')->default(0);
            $table->timestamps();

            $table->index(['tenant_id', 'inspection_template_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_template_items');
        Schema::dropIfExists('inspection_templates');
    }
};
