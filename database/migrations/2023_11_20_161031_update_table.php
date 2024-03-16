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
        Schema::table('activity_content_criteria', function(Blueprint $table) {
            $table->string('criteria_title')->nullable()->change();
            $table->string('score')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_content_criteria', function(Blueprint $table) {
            $table->dropColumn('criteria_title');
            $table->dropColumn('score');
        });
    }
};
