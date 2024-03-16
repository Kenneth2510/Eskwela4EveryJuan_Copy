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
        Schema::create('learner_quiz_output', function (Blueprint $table) {
            $table->id('learner_quiz_output_id');
            $table->unsignedBigInteger('learner_course_id');
            $table->unsignedBigInteger('learner_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('syllabus_id');
            $table->unsignedBigInteger('quiz_id');;
            $table->unsignedBigInteger('quiz_content_id');
            $table->string('answer')->nullable();
            $table->tinyInteger('isCorrect')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learner_quiz_output');
    }
};
