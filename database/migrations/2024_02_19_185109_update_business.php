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
        Schema::table('business', function(Blueprint $table) {
            $table->string('business_classification')->nullable()->after('business_category');
            $table->longText('business_description')->nullable()->after('business_classification');        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business', function(Blueprint $table) {
            $table->dropColumn('business_classification');
            $table->dropColumn('business_description');
        });
    }
};
