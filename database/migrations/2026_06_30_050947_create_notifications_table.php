<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        // Laravel's built-in notifications table بس بنضيف tenant_id
        Schema::create('notifications', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
            $table->string('type');
            // اسم الـ Notification class
            $table->morphs('notifiable');
            // notifiable_type + notifiable_id
            $table->json('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'notifiable_id', 'read_at']);
        });

        Schema::create('notification_templates', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
            $table->string('event');
            // 'request.created', 'inspection.failed'
            $table->string('title');
            $table->text('body');
            $table->json('channels');
            // ['in_app', 'whatsapp', 'email']
            $table->json('roles');
            // ['quality', 'operations', 'admin']
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['tenant_id', 'event']);
            $table->index('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('notifications');
    }
};
