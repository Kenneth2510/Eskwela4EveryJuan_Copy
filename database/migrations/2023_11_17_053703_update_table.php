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
        Schema::table('learner_activity_criteria_score', function(Blueprint $table) {
            $table->unsignedBigInteger('learner_activity_output_id')->after('learner_activity_criteria_score_id');
        
            $table->foreign('learner_activity_output_id', 'fk_learner_activity_output')->references('learner_activity_output_id')->on('learner_activity_output')->onDelete('cascade')->onUpdate('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learner_activity_criteria_score', function(Blueprint $table) {
            $table->dropColumn('learner_activity_output_id');
        });
    }
};
