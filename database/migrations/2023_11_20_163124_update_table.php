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
        // Drop the foreign key constraint
        Schema::table('quiz_reference', function(Blueprint $table) {
            $table->dropForeign('quiz_reference_syllabus_id_foreign');
        });

        // Change the column to nullable
        Schema::table('quiz_reference', function(Blueprint $table) {
            $table->string('syllabus_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add the foreign key constraint back
        Schema::table('quiz_reference', function(Blueprint $table) {
            // Assuming you know the referenced table and column, replace 'referenced_table' and 'referenced_column'
            $table->foreign('syllabus_id')
                ->references('id')
                ->on('referenced_table')
                ->onDelete('cascade');  // Adjust the onDelete behavior as needed
        });
    }
};
