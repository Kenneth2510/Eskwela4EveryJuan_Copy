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
        Schema::create('activity_content_criteria', function (Blueprint $table) {
            $table->id('activity_content_criteria_id');
            $table->unsignedBigInteger('activity_content_id');
            $table->string('criteria_title');
            $table->integer('score');
            $table->timestamps();

            $table->foreign('activity_content_id')->references('activity_content_id')->on('activity_content')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_content_criteria');
    }
};
