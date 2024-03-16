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
        Schema::table('learner_post_assessment_output', function(Blueprint $table) {
            $table->string('answer')->nullable()->change();
        });

        Schema::table('learner_pre_assessment_output', function(Blueprint $table) {
            $table->string('answer')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
