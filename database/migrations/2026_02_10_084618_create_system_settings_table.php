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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('allow_guest_reading')->default(true);
            $table->boolean('allow_non_user_reading')->default(false);
            $table->unsignedInteger('guest_preview_pages')->default(0);
            $table->unsignedInteger('user_preview_pages')->default(0);
            $table->boolean('watermark_enabled')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
