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
        Schema::table('activity_content', function(Blueprint $table) {
            $table->string('activity_instructions')->nullable()->change();
            $table->string('total_score')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_content', function(Blueprint $table) {
            $table->dropColumn('activity_instructions');
            $table->dropColumn('total_score');
        });
    }
};
