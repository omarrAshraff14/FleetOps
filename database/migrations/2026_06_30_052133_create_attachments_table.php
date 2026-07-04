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
       Schema::create('attachments', function (Blueprint $table) {
    $table->ulid('id')->primary();
    $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
    $table->ulidMorphs('attachable');
    // attachable_type + attachable_id
    $table->foreignUlid('uploaded_by')->constrained('users')->restrictOnDelete();
    $table->enum('type', [
        'departure_meter',  // عداد الخروج - إجباري
        'return_meter',     // عداد الرجوع - إجباري
        'departure_car',    // صورة العربية خروج
        'return_car',       // صورة العربية رجوع
        'fuel',
        'damage',
        'repair',
        'document',
        'signature',
        'other'
    ]);
    $table->string('file_path');
    $table->string('file_name');
    $table->string('mime_type')->nullable();
    $table->unsignedInteger('file_size')->nullable(); // bytes
    $table->text('notes')->nullable();
    $table->timestamps();

    $table->index(['tenant_id', 'attachable_type', 'attachable_id']);
    $table->index(['attachable_type', 'attachable_id', 'type']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
