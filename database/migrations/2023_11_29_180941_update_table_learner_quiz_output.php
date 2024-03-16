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
        Schema::table('learner_quiz_output', function (Blueprint $table) {
            $table->integer('attempts')->default(0)->after('quiz_content_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learner_quiz_output', function (Blueprint $table) {
            $table->dropColumn(['attempts']);
        });
    }
};
