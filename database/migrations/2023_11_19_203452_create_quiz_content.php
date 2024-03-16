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
        Schema::create('quiz_content', function (Blueprint $table) {
            $table->id('quiz_content_id');
            $table->unsignedBigInteger('quiz_id');
            $table->unsignedBigInteger('syllabus_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('question_id');
            $table->timestamps();

            $table->foreign('quiz_id')->references('quiz_id')->on('quizzes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('syllabus_id')->references('syllabus_id')->on('syllabus')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('course_id')->references('course_id')->on('course')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('question_id')->references('question_id')->on('questions')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_content');
    }
};
