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
        Schema::create('learner', function (Blueprint $table) {
            $table->id('learner_id');
            $table->string('status');
            $table->string('learner_username');
            $table->string('password');
            $table->string('learner_security_code');
            $table->string('learner_fname');
            $table->string('learner_lname');
            $table->date('learner_bday');
            $table->string('learner_gender');
            $table->string('learner_contactno')->nullable()->unique();
            $table->string('learner_email')->nullable()->unique();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learner');
    }
};
