<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
            $table->foreignUlid('branch_id')
                  ->constrained('branches')
                  ->restrictOnDelete();
            $table->foreignUlid('car_model_id')
                  ->constrained('car_models')
                  ->restrictOnDelete();

            // بيانات أساسية
            $table->string('code')->nullable();
            // كود داخلي للشركة
            $table->string('plate_number');
            $table->year('year');
            $table->string('color');
            $table->string('chassis_number')->nullable();
            $table->string('engine_number')->nullable();
            $table->enum('fuel_type', [
                'petrol',
                'diesel',
                'electric',
                'hybrid'
            ])->default('petrol');
            $table->unsignedInteger('current_km')->default(0);

            // ملكية وإدارة
            $table->string('owner_name')->nullable();
            // لو العربية مش ملك الشركة
            $table->string('supplier_name')->nullable();
            $table->string('account_manager')->nullable();

            // مميزات العربية
            $table->json('features')->nullable();
            // {
            //   "abs": true,
            //   "cruise_control": true,
            //   "bluetooth": true,
            //   "aux": true,
            //   "airbags": 6,
            //   "sunroof": false
            // }
            $table->boolean('has_camera')->default(false);
            $table->boolean('has_sensors')->default(false);

            // حالة العربية
            $table->enum('status', [
                'ready',
                'not_ready',
                'in_use',
                'maintenance',
                'retired'
            ])->default('ready');

            // Override بتاع المدير
            $table->foreignUlid('status_override_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->string('status_override_note')->nullable();
            $table->timestamp('status_override_at')->nullable();

            // السائق الحالي
            $table->foreignUlid('current_driver_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->unique(['tenant_id', 'plate_number']);
            $table->unique(['tenant_id', 'code']);
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'branch_id']);
            $table->index(['tenant_id', 'current_driver_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};