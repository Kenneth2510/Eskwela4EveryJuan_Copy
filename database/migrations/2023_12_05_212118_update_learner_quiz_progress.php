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
            $table->integer('attempt')->after('max_attempt')->default(0);
            $table->integer('score')->after('attempt')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learner_quiz_progress', function (Blueprint $table) {
            $table->dropColumn('attempt');
            $table->dropColumn('score');
        });
    }
};
