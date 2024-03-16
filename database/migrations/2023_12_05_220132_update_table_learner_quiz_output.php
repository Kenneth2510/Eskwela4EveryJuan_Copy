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
        Schema::table('learner_quiz_progress', function (Blueprint $table) {
            $table->integer('attempt')->default(1)->change();
        });
        Schema::table('learner_quiz_output', function (Blueprint $table) {
            $table->integer('attempts')->default(1)->change();
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
