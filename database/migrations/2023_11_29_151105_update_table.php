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
        Schema::table('questions', function (Blueprint $table) {
            $table->longText('question')->change();
        });
        Schema::table('activity_content', function (Blueprint $table) {
            $table->longText('activity_instructions')->change();
        });
        Schema::table('lesson_content', function (Blueprint $table) {
            $table->longText('lesson_content_title')->nullable()->change();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
