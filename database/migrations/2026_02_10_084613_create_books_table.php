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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('file_path')->nullable();
            $table->tinyInteger('status')->default(0); // 0 = Draft, 1 = Published
            $table->tinyInteger('access_level')->default(1); // 0 = Public, 1 = Private, 2 = Premium
            $table->integer('pages')->nullable();
            $table->string('language')->nullable();
            $table->string('isbn')->nullable();
            $table->date('published_date')->nullable();
            $table->integer('views')->default(0);
            $table->integer('downloads')->default(0);
            $table->timestamp('create_at')->useCurrent();
            $table->timestamps(); // Add created_at and updated_at

            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
