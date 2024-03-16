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
        // Add an index to the 'topic_id' column in the 'syllabus' table
        Schema::table('syllabus', function (Blueprint $table) {
            $table->index('topic_id');
        });

        // Modify the 'lessons' table
        Schema::table('lessons', function (Blueprint $table) {
            $table->integer('topic_id')->after('syllabus_id');
            $table->foreign('topic_id')->references('topic_id')->on('syllabus')->onDelete('cascade')->onUpdate('cascade');
        });

        // Modify the 'activities' table
        Schema::table('activities', function (Blueprint $table) {
            $table->integer('topic_id')->after('syllabus_id');
            $table->foreign('topic_id')->references('topic_id')->on('syllabus')->onDelete('cascade')->onUpdate('cascade');
        });

        // Modify the 'quizzes' table
        Schema::table('quizzes', function (Blueprint $table) {
            $table->integer('topic_id')->after('syllabus_id');
            $table->foreign('topic_id')->references('topic_id')->on('syllabus')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the changes for 'quizzes' and 'activities' tables
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
            $table->dropColumn('topic_id');
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
            $table->dropColumn('topic_id');
        });

        // Reverse the changes for the 'lessons' table
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
            $table->dropColumn('topic_id');
        });

        // Remove the index from the 'syllabus' table
        Schema::table('syllabus', function (Blueprint $table) {
            $table->dropIndex(['topic_id']);
        });
    }
};
