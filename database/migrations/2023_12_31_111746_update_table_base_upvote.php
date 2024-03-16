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
        Schema::table('thread_upvotes', function(Blueprint $table) {
            $table->integer('base_upvote')->default(0)->change();
        });

        Schema::table('thread_comment_upvotes', function(Blueprint $table) {
            $table->integer('base_upvote')->default(0)->change();
        });

        Schema::table('thread_comment_reply_upvotes', function(Blueprint $table) {
            $table->integer('base_upvote')->default(0)->change();
        });

        Schema::table('thread_reply_reply_upvotes', function(Blueprint $table) {
            $table->integer('base_upvote')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
