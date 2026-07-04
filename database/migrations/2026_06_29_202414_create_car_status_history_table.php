<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_status_history', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
            $table->foreignUlid('car_id')
                  ->constrained('cars')
                  ->cascadeOnDelete();
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->foreignUlid('changed_by')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->boolean('is_override')->default(false);
            // هل ده override من المدير؟
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'car_id']);
            $table->index(['car_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_status_history');
    }
};
