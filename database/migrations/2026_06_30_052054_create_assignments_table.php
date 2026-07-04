<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
    $table->ulid('id')->primary();
    $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
    $table->foreignUlid('request_id')->constrained('requests')->cascadeOnDelete();
    $table->foreignUlid('driver_id')->constrained('users')->restrictOnDelete();
    $table->foreignUlid('rep_id')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignUlid('companion_id')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignUlid('assigned_by')->constrained('users')->restrictOnDelete();
    $table->enum('status', ['active', 'cancelled', 'replaced'])->default('active');
    $table->text('cancel_reason')->nullable();
    $table->timestamps();

    $table->index(['tenant_id', 'request_id']);
    $table->index(['tenant_id', 'driver_id', 'status']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
