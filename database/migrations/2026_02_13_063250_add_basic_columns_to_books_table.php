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
        Schema::table('books', function (Blueprint $table) {
            // Add only the essential columns we need
            if (!Schema::hasColumn('books', 'views')) {
                $table->integer('views')->default(0);
            }
            if (!Schema::hasColumn('books', 'downloads')) {
                $table->integer('downloads')->default(0);
            }
            if (!Schema::hasColumn('books', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable();
            }
            if (!Schema::hasColumn('books', 'created_at')) {
                $table->timestamp('created_at')->useCurrent();
            }
            if (!Schema::hasColumn('books', 'updated_at')) {
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['views', 'downloads', 'category_id', 'created_at', 'updated_at']);
        });
    }
};
