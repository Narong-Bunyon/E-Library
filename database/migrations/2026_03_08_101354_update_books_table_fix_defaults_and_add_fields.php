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
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('books', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('author_id');
            }
            if (!Schema::hasColumn('books', 'pages')) {
                $table->integer('pages')->nullable()->after('access_level');
            }
            if (!Schema::hasColumn('books', 'language')) {
                $table->string('language')->nullable()->after('pages');
            }
            if (!Schema::hasColumn('books', 'isbn')) {
                $table->string('isbn')->nullable()->after('language');
            }
            if (!Schema::hasColumn('books', 'published_date')) {
                $table->date('published_date')->nullable()->after('isbn');
            }
            if (!Schema::hasColumn('books', 'views')) {
                $table->integer('views')->default(0)->after('published_date');
            }
            if (!Schema::hasColumn('books', 'downloads')) {
                $table->integer('downloads')->default(0)->after('views');
            }
            if (!Schema::hasColumn('books', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('create_at');
            }
            if (!Schema::hasColumn('books', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });

        // Add foreign keys if they don't exist and categories table exists
        if (Schema::hasColumn('books', 'category_id') && Schema::hasTable('categories')) {
            Schema::table('books', function (Blueprint $table) {
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            });
        }

        // Update existing records to have private as default (only if access_level is currently null or 0)
        \DB::statement("UPDATE books SET access_level = 1 WHERE access_level = 0 OR access_level IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Drop foreign keys first (only if it exists)
            try {
                $table->dropForeign(['category_id']);
            } catch (\Exception $e) {
                // Foreign key doesn't exist, continue
            }
            
            // Drop columns if they exist
            if (Schema::hasColumn('books', 'category_id')) {
                $table->dropColumn('category_id');
            }
            if (Schema::hasColumn('books', 'pages')) {
                $table->dropColumn('pages');
            }
            if (Schema::hasColumn('books', 'language')) {
                $table->dropColumn('language');
            }
            if (Schema::hasColumn('books', 'isbn')) {
                $table->dropColumn('isbn');
            }
            if (Schema::hasColumn('books', 'published_date')) {
                $table->dropColumn('published_date');
            }
            if (Schema::hasColumn('books', 'views')) {
                $table->dropColumn('views');
            }
            if (Schema::hasColumn('books', 'downloads')) {
                $table->dropColumn('downloads');
            }
            if (Schema::hasColumn('books', 'created_at')) {
                $table->dropColumn('created_at');
            }
            if (Schema::hasColumn('books', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });
    }
};
