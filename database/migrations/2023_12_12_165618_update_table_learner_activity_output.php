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
        Schema::table('learner_activity_output', function(Blueprint $table) {
            $table->integer('max_attempt')->default('2')->after('remarks');
            $table->integer('attempt')->default('1')->after('max_attempt');
            $table->string('mark')->nullable()->after('attempt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learner_activity_output', function(Blueprint $table) {
            $table->dropColumn(['max_attempt', 'attempt', 'mark']);
        });
    }
};
