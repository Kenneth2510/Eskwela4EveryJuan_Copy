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
        Schema::create('message_content_file', function (Blueprint $table) {
            $table->id("message_content_file_id");
            $table->unsignedBigInteger("message_id");
            $table->unsignedBigInteger("message_content_id");
            $table->longText("message_content_file");
            $table->timestamps();

            $table->foreign("message_id")->references("message_id")->on("message")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("message_content_id")->references("message_content_id")->on("message_content")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_content_file');
    }
};
