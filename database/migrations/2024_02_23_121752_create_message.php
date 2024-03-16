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
        Schema::create('message', function (Blueprint $table) {
            $table->id("message_id");
            $table->unsignedBigInteger("message_content_id");
            $table->string("sender_user_type");
            $table->string("sender_user_email");
            $table->string("receiver_user_type");
            $table->string("receiver_user_email");
            $table->dateTime("date_sent");
            $table->tinyInteger("isRead")->default(0);
            $table->dateTime("date_read")->nullable();
            $table->timestamps();

            $table->foreign('message_content_id')->references('message_content_id')->on("message_content")->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message');
    }
};
