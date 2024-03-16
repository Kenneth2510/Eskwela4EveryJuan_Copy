<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('instructor', function (Blueprint $table) {
            $table->string('profile_picture')->nullable(); // You can change 'string' to 'text' if storing URLs.
        });

        Schema::table('learner', function (Blueprint $table) {
            $table->string('profile_picture')->nullable(); // You can change 'string' to 'text' if storing URLs.
        });
    }

    public function down()
    {
        Schema::table('instructor', function (Blueprint $table) {
            $table->dropColumn('profile_picture');
        });

        Schema::table('learner', function (Blueprint $table) {
            $table->dropColumn('profile_picture');
        });
    }
};
