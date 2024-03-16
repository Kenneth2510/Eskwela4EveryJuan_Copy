<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::table('instructor', function (Blueprint $table) {
    //         $table->dropColumn('instructor_credentials');
    //     });

    //     Schema::table('instructor', function (Blueprint $table) {
    //         $table->longText('instructor_credentials')->after('instructor_email');
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     Schema::table('instructor', function (Blueprint $table) {
    //         $table->dropColumn('instructor_credentials');
    //     });

    //     Schema::table('instructor', function (Blueprint $table) {
    //         $table->string('instructor_credentials')->after('instructor_email');
    //     });
    // }
};
