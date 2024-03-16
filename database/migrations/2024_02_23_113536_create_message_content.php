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
        Schema::create('message_content', function (Blueprint $table) {
            $table->id("message_content_id");
            $table->longText("message_subject");
            $table->longText("message_content");
            $table->tinyInteger("message_has_file")->default(0);
            $table->dateTime("date_updated");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_content');
    }
};
