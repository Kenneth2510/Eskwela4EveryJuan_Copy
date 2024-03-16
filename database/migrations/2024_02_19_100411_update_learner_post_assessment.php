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
        Schema::table('learner_post_assessment_progress', function(Blueprint $table) {
            $table->integer('attempt')->default(1)->after('finish_period');
        });

        Schema::table('learner_post_assessment_output', function(Blueprint $table) {
            $table->integer('attempt')->default(1)->after('syllabus_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learner_post_assessment_progress', function(Blueprint $table) {
            $table->dropColumn('attempt');
        });

        Schema::table('learner_post_assessment_output', function(Blueprint $table) {
            $table->dropColumn('attempt');
        });
    }
};
