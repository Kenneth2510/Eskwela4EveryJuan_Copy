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
        Schema::create('business', function (Blueprint $table) {
            $table->id('business_id');
            $table->string('business_name');
            $table->string('business_address');
            $table->string('business_owner_name');
            $table->string('bplo_account_number');
            $table->string('business_category');
            $table->unsignedBigInteger('learner_id');
            $table->timestamps();

            $table->foreign('learner_id')->references('learner_id')->on('learner')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business');
    }
};
