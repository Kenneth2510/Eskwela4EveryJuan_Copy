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
        Schema::create('message_reply_content_file', function (Blueprint $table) {
            $table->id("message_reply_content_file_id");
            $table->unsignedBigInteger("message_reply_id");
            $table->unsignedBigInteger("message_reply_content_id");
            $table->longText("message_reply_content_file");
            $table->timestamps();

            
            $table->foreign("message_reply_id")->references("message_reply_id")->on("message_reply")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("message_reply_content_id")->references("message_reply_content_id")->on("message_reply_content")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_reply_content_file');
    }
};
