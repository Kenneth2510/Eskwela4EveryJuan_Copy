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
        Schema::create('learner_course', function (Blueprint $table) {
            $table->id('learner_course_id');
            $table->unsignedBigInteger('learner_id');
            $table->unsignedBigInteger('course_id');
            $table->timestamps();

            $table->foreign('learner_id')->references('learner_id')->on('learner')->onDelete('cascade');
            $table->foreign('course_id')->references('course_id')->on('course')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learner_course');
    }
};
