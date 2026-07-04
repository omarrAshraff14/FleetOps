<?php
// database/migrations/2025_01_01_000001_create_tenants_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('domain')->unique();
            $table->string('logo')->nullable();
            $table->json('settings')->nullable();
            // settings مثال:
            // {
            //   "timezone": "Africa/Cairo",
            //   "currency": "EGP",
            //   "date_format": "d/m/Y",
            //   "max_branches": 10
            // }
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};