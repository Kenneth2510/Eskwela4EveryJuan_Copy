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
        Schema::create('message_reply_content', function (Blueprint $table) {
            $table->id("message_reply_content_id");
            $table->unsignedBigInteger("message_reply_id");
            $table->longText("message_reply_content");
            $table->tinyInteger("message_has_file")->default(0);
            $table->timestamps();

           $table->foreign("message_reply_id")->references("message_reply_id")->on("message_reply")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_reply_content');
    }
};
