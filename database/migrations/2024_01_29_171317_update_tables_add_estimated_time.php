<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('activities', function(Blueprint $table) {
            $table->bigInteger('duration')->after('activity_title')->default(0);
        });

        Schema::table('lessons', function(Blueprint $table) {
            $table->bigInteger('duration')->after('lesson_title')->default(0);
        });
    }


    public function down(): void
    {
        Schema::table('activities', function(Blueprint $table) {
            $table->dropColumn('duration');
        });

        Schema::table('lessons', function(Blueprint $table) {
            $table->dropColumn('duration');
        });
    }
};
