<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timesheets', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
            $table->foreignUlid('branch_id')
                  ->constrained('branches')
                  ->restrictOnDelete();
            $table->foreignUlid('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->unsignedSmallInteger('total_minutes')
                  ->nullable();
            // check_out - check_in
            $table->enum('status', [
                'present',
                'absent',
                'leave',
                'holiday'
            ])->default('present');
            $table->foreignUlid('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'user_id', 'date']);
            $table->index(['tenant_id', 'user_id']);
            $table->index(['tenant_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timesheets');
    }
};

