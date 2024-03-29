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
        $triggerName = 'add_new_learner_syllabus_progress_status';
        $tableName = 'learner_syllabus_progress';

        DB::unprepared("DROP TRIGGER IF EXISTS $triggerName");

        
            DB::unprepared("
                CREATE TRIGGER $triggerName
                BEFORE INSERT ON $tableName
                FOR EACH ROW
                SET NEW.status = 'LOCKED';
            ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $triggerName = 'add_new_learner_syllabus_progress_status';

        DB::unprepared("DROP TRIGGER IF EXISTS $triggerName");
    }
};
