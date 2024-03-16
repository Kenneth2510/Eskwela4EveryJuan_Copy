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
        Schema::create('learner_activity_output', function (Blueprint $table) {
            $table->id('learner_activity_output_id');
            $table->unsignedBigInteger('learner_course_id');
            $table->unsignedBigInteger('syllabus_id');
            $table->unsignedBigInteger('activity_id');
            $table->unsignedBigInteger('activity_content_id');
            $table->unsignedBigInteger('course_id');
            $table->longText('answer');
            $table->integer('total_score')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->foreign('learner_course_id')->references('learner_course_id')->on('learner_course')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('syllabus_id')->references('syllabus_id')->on('syllabus')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('activity_id')->references('activity_id')->on('activities')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('activity_content_id')->references('activity_content_id')->on('activity_content')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('course_id')->references('course_id')->on('course')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learner_activity_output');
    }
};
