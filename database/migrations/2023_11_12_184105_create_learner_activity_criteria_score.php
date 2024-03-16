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
        Schema::create('learner_activity_criteria_score', function (Blueprint $table) {
            $table->id('learner_activity_criteria_score_id');
            $table->unsignedBigInteger('activity_content_criteria_id');
            $table->unsignedBigInteger('activity_content_id');
            $table->integer('score')->nullable();
            $table->timestamps();

            // Specify names for the foreign key constraints
            $table->foreign('activity_content_criteria_id', 'fk_criteria_score_criteria_id')->references('activity_content_criteria_id')->on('activity_content_criteria')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('activity_content_id', 'fk_criteria_score_content_id')->references('activity_content_id')->on('activity_content')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Specify names for the foreign key constraints in the down method as well
        Schema::dropIfExists('learner_activity_criteria_score');
    }
};
