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
        Schema::create('course_grading', function (Blueprint $table) {
            $table->id('course_grading_id');
            $table->unsignedBigInteger('course_id');
            $table->float('activity_percent')->default(0.35);
            $table->float('quiz_percent')->default(0.35);
            $table->float('pre_assessment_percent')->default(0.30);
            $table->float('post_assessment_percent')->default(0.30);
            $table->timestamps();

            $table->foreign('course_id')->references('course_id')->on('course')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_grading');
    }
};
