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
        Schema::table('learner_course_progress', function(Blueprint $table) {
            $table->integer('grade')->default(0)->after('course_progress');
            $table->string('remarks')->nullable()->after('grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learner_course_progress', function(Blueprint $table) {
            $table->dropColumn('grade');
            $table->dropColumn('remarks');
        });
    }
};
