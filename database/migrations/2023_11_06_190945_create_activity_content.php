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
        Schema::create('activity_content', function (Blueprint $table) {
            $table->id('activity_content_id');
            $table->unsignedBigInteger('activity_id');
            $table->string('activity_instructions');
            $table->integer('total_score');
            $table->timestamps();

            $table->foreign('activity_id')->references('activity_id')->on('activities')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_content');
    }
};
