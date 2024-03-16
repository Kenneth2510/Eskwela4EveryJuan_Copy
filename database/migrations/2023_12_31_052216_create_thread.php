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
        Schema::create('thread', function (Blueprint $table) {
            $table->id('thread_id');
            $table->unsignedBigInteger('user_id');
            $table->string('user_type');
            $table->timestamps();
        });


        Schema::create('thread_contents', function (Blueprint $table) {
            $table->id('thread_content_id');
            $table->unsignedBigInteger('thread_id');
            $table->string('thread_type');
            $table->string('thread_title', 300);
            $table->longText('thread_content');
            $table->timestamps();

            $table->foreign('thread_id')->references('thread_id')->on('thread')->onDelete('cascade')->onUpdate('cascade');
        });

        
        Schema::create('thread_comments', function (Blueprint $table) {
            $table->id('thread_comment_id');
            $table->unsignedBigInteger('thread_id');
            $table->unsignedBigInteger('user_id');
            $table->string('user_type');
            $table->longText('thread_comment');
            $table->timestamps();

            $table->foreign('thread_id')->references('thread_id')->on('thread')->onDelete('cascade')->onUpdate('cascade');
        });


        Schema::create('thread_comment_replies', function (Blueprint $table) {
            $table->id('thread_comment_reply_id');
            $table->unsignedBigInteger('thread_id');
            $table->unsignedBigInteger('thread_comment_id');
            $table->unsignedBigInteger('user_id');
            $table->string('user_type');
            $table->longText('thread_comment_reply');
            $table->timestamps();

            $table->foreign('thread_id')->references('thread_id')->on('thread')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('thread_comment_id')->references('thread_comment_id')->on('thread_comments')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('thread_reply_replies', function (Blueprint $table) {
            $table->id('thread_reply_reply_id');
            $table->unsignedBigInteger('thread_id');
            $table->unsignedBigInteger('thread_comment_id');
            $table->unsignedBigInteger('thread_comment_reply_id');
            $table->unsignedBigInteger('user_id');
            $table->string('user_type');
            $table->longText('thread_reply_reply');
            $table->timestamps();

            $table->foreign('thread_id')->references('thread_id')->on('thread')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('thread_comment_id')->references('thread_comment_id')->on('thread_comments')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('thread_comment_reply_id')->references('thread_comment_reply_id')->on('thread_comment_replies')->onDelete('cascade')->onUpdate('cascade');
        });


        Schema::create('thread_upvotes', function (Blueprint $table) {
            $table->id('thread_upvote_id');
            $table->unsignedBigInteger('thread_id');
            $table->integer('base_upvote');
            $table->integer('randomized_display_upvote');
            $table->dateTime('last_randomized_datetime');
            $table->timestamps();

            $table->foreign('thread_id')->references('thread_id')->on('thread')->onDelete('cascade')->onUpdate('cascade');
        });


        Schema::create('thread_comment_upvotes', function (Blueprint $table) {
            $table->id('thread_comment_upvote_id');
            $table->unsignedBigInteger('thread_id');
            $table->unsignedBigInteger('thread_comment_id');
            $table->integer('base_upvote');
            $table->integer('randomized_display_upvote');
            $table->dateTime('last_randomized_datetime');
            $table->timestamps();

            $table->foreign('thread_id')->references('thread_id')->on('thread')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('thread_comment_id')->references('thread_comment_id')->on('thread_comments')->onDelete('cascade')->onUpdate('cascade');
        });


        Schema::create('thread_comment_reply_upvotes', function (Blueprint $table) {
            $table->id('thread_comment_reply_upvote_id');
            $table->unsignedBigInteger('thread_id');
            $table->unsignedBigInteger('thread_comment_id');
            $table->unsignedBigInteger('thread_comment_reply_id');
            $table->integer('base_upvote');
            $table->integer('randomized_display_upvote');
            $table->dateTime('last_randomized_datetime');
            $table->timestamps();

            $table->foreign('thread_id')->references('thread_id')->on('thread')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('thread_comment_id')->references('thread_comment_id')->on('thread_comments')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('thread_comment_reply_id')->references('thread_comment_reply_id')->on('thread_comment_replies')->onDelete('cascade')->onUpdate('cascade');
        });


        Schema::create('thread_reply_reply_upvotes', function (Blueprint $table) {
            $table->id('thread_reply_reply_upvote_id');
            $table->unsignedBigInteger('thread_id');
            $table->unsignedBigInteger('thread_comment_id');
            $table->unsignedBigInteger('thread_comment_reply_id');
            $table->unsignedBigInteger('thread_reply_reply_id');
            $table->integer('base_upvote');
            $table->integer('randomized_display_upvote');
            $table->dateTime('last_randomized_datetime');
            $table->timestamps();

            $table->foreign('thread_id')->references('thread_id')->on('thread')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('thread_comment_id')->references('thread_comment_id')->on('thread_comments')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('thread_comment_reply_id')->references('thread_comment_reply_id')->on('thread_comment_replies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('thread_reply_reply_id')->references('thread_reply_reply_id')->on('thread_reply_replies')->onDelete('cascade')->onUpdate('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thread_reply_reply_upvotes');
        Schema::dropIfExists('thread_comment_reply_upvotes');
        Schema::dropIfExists('thread_comment_upvotes');
        Schema::dropIfExists('thread_upvotes');
        Schema::dropIfExists('thread_reply_replies');
        Schema::dropIfExists('thread_comment_replies');
        Schema::dropIfExists('thread_comments');
        Schema::dropIfExists('thread_contents');
        Schema::dropIfExists('thread_user');
        Schema::dropIfExists('threads');
    }
};
