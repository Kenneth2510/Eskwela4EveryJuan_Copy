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
        Schema::create('learner_pre_assessment_progress', function (Blueprint $table) {
            $table->id('learner_pre_assessment_progress_id');
            $table->unsignedBigInteger('learner_course_id');
            $table->unsignedBigInteger('learner_id');
            $table->unsignedBigInteger('course_id');
            $table->string('status')->default('NOT YET STARTED');
            $table->bigInteger('max_duration')->default(2700000);
            $table->dateTime('start_period')->nullable();
            $table->dateTime('finish_period')->nullable();
            $table->integer('score')->default(0);
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->foreign('learner_course_id')->references('learner_course_id')->on('learner_course')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('course_id')->references('course_id')->on('course')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('learner_id')->references('learner_id')->on('learner')->onDelete('cascade')->onUpdate('cascade');
        });


        Schema::create('learner_pre_assessment_output', function (Blueprint $table) {
            $table->id('learner_pre_assessment_output_id');
            $table->unsignedBigInteger('learner_course_id');
            $table->unsignedBigInteger('learner_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('question_id');
            $table->integer('isCorrect')->default(0);
            $table->timestamps();

            $table->foreign('learner_course_id')->references('learner_course_id')->on('learner_course')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('course_id')->references('course_id')->on('course')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('learner_id')->references('learner_id')->on('learner')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('question_id')->references('question_id')->on('questions')->onDelete('cascade')->onUpdate('cascade');
           });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learner_pre_assessment_progress');

        Schema::dropIfExists('learner_pre_assessment_output');
    }
};
