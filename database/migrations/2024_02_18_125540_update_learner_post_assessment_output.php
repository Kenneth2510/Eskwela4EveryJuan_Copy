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
        Schema::table('learner_post_assessment_output', function(Blueprint $table) {
            $table->unsignedBigInteger('syllabus_id')->after('question_id');
            $table->string('answer')->after('syllabus_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learner_post_assessment_output', function(Blueprint $table) {
            $table->dropColumn('syllabus_id');
            $table->dropColumn('answer');
        });
    }
};
