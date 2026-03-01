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
        Schema::create('reading_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->integer('pages_read')->default(0);
            $table->integer('total_pages')->default(0);
            $table->text('notes')->nullable();
            $table->enum('status', ['reading', 'completed', 'paused', 'abandoned'])->default('reading');
            $table->timestamps();
            
            $table->index(['user_id', 'book_id']);
            $table->index('user_id');
            $table->index('book_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_histories');
    }
};
