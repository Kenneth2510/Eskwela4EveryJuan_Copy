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
        Schema::create('course', function (Blueprint $table) {
            $table->id('course_id');
            $table->string('course_name');
            $table->string('course_code');
            $table->longText('course_description');
            $table->string('course_status');
            $table->string('course_difficulty');
            $table->unsignedBigInteger('instructor_id');
            $table->timestamps();

            $table->foreign('instructor_id')->references('instructor_id')->on('instructor')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course');
    }
};
