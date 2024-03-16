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
        Schema::table('learner_course_progress', function(Blueprint $table) {
            $table->dateTime('start_period')->nullable()->after('course_progress');
            $table->dateTime('finish_period')->nullable()->after('start_period');
        });

        Schema::table('learner_lesson_progress', function(Blueprint $table) {
            $table->dateTime('start_period')->nullable()->after('status');
            $table->dateTime('finish_period')->nullable()->after('start_period');
        });

        Schema::table('learner_activity_progress', function(Blueprint $table) {
            $table->dateTime('start_period')->nullable()->after('status');
            $table->dateTime('finish_period')->nullable()->after('start_period');
        });

        Schema::table('learner_quiz_progress', function(Blueprint $table) {
            $table->dateTime('start_period')->nullable()->after('status');
            $table->dateTime('finish_period')->nullable()->after('start_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learner_course_progress', function(Blueprint $table) {
            $table->dropColumn('start_period');
            $table->dropColumn('finish_period');
        });

        Schema::table('learner_lesson_progress', function(Blueprint $table) {
            $table->dropColumn('start_period');
            $table->dropColumn('finish_period');
        });

        Schema::table('learner_activity_progress', function(Blueprint $table) {
            $table->dropColumn('start_period');
            $table->dropColumn('finish_period');
        });

        Schema::table('learner_quiz_progress', function(Blueprint $table) {
            $table->dropColumn('start_period');
            $table->dropColumn('finish_period');
        });
    }
};
