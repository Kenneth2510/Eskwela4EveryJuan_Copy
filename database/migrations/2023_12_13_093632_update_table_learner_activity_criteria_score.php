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
        Schema::table('learner_activity_criteria_score', function(Blueprint $table) {
            $table->integer('attempt')->default(1)->nullable()->after('activity_content_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learner_activity_criteria_score', function(Blueprint $table) {
            $table->dropColumn('attempt');
        });
    }
};
