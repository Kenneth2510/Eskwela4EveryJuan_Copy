<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('admin_accounts', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });

        DB::table('admin')->insert([
            'admin_username' => 'admin1',
            'admin_codename' => 'Eskwela4EveryJuan_Admin1',
            'role' => 'SUPER_ADMIN',
            'email' => 'eskwela4everyjuan@gmail.com',
            'password' => bcrypt('eskwela4everyjuan_admin1'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('admin')->insert([
            'admin_username' => 'admin2',
            'admin_codename' => 'Eskwela4EveryJuan_Admin2',
            'role' => 'SUPER_ADMIN',
            'email' => 'eskwela4everyjuan@gmail.com',
            'password' => bcrypt('eskwela4everyjuan_admin2'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('admin')->insert([
            'admin_username' => 'admin3',
            'admin_codename' => 'Eskwela4EveryJuan_Admin3',
            'role' => 'SUPER_ADMIN',
            'email' => 'eskwela4everyjuan@gmail.com',
            'password' => bcrypt('eskwela4everyjuan_admin3'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_accounts');
    }
};
