<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->integer('course_id')->after('lesson_id'); // You can change 'string' to 'text' if storing URLs.
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->integer('course_id')->after('activity_id'); // You can change 'string' to 'text' if storing URLs.
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->integer('course_id')->after('quiz_id'); // You can change 'string' to 'text' if storing URLs.
        });
    }

    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('course_id');
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('course_id');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('course_id');
        });
    }
};
