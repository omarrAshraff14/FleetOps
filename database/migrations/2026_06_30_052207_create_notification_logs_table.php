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
      Schema::create('notification_logs', function (Blueprint $table) {
    $table->ulid('id')->primary();
    $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
    $table->foreignUlid('notification_id')->constrained('notifications')->cascadeOnDelete();
    $table->string('channel'); // in_app, whatsapp, email
    $table->enum('status', ['sent', 'failed', 'pending'])->default('pending');
    $table->text('error_message')->nullable();
    $table->timestamp('sent_at')->nullable();
    $table->timestamps();

    $table->index(['tenant_id', 'status']);
    $table->index(['notification_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
