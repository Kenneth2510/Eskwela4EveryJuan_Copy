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
        Schema::table('lesson_content', function (Blueprint $table) {
            // Make the 'lesson_content' column nullable
            $table->longText('lesson_content')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // In case you want to rollback the changes, you can implement the reverse logic here.
        // However, making a column nullable usually doesn't require a specific rollback operation.
    }
};
