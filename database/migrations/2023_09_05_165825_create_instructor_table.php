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
        Schema::create('instructor', function (Blueprint $table) {
            $table->id('instructor_id');
            $table->string('status');
            $table->string('instructor_username');
            $table->string('password');
            $table->string('instructor_security_code');
            $table->string('instructor_fname');
            $table->string('instructor_lname');
            $table->date('instructor_bday');
            $table->string('instructor_gender');
            $table->string('instructor_contactno')->nullable()->unique();
            $table->string('instructor_email')->nullable()->unique();
            $table->longText('instructor_credentials');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor');
    }
};
