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
            $table->integer('pages')->nullable()->after('description');
            $table->string('language', 50)->default('English')->after('pages');
            $table->string('isbn', 20)->nullable()->after('language');
            $table->date('published_date')->nullable()->after('isbn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['pages', 'language', 'isbn', 'published_date']);
        });
    }
};
