<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ModifySubjectsForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration changes the foreign key on `subjects`.`teacher_code`
     * from ON DELETE CASCADE to ON DELETE SET NULL and makes the column nullable.
     * That way deleting a teacher will not remove the subject rows.
     */
    public function up()
    {
        // Temporarily disable foreign key checks while altering.
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop existing foreign key. The original constraint name in the SQL dump
        // is `fk_subject_teacher` so we remove that.
        DB::statement('ALTER TABLE `subjects` DROP FOREIGN KEY `fk_subject_teacher`');

        // Make teacher_code nullable
        DB::statement('ALTER TABLE `subjects` MODIFY `teacher_code` varchar(64) NULL');

        // Recreate foreign key with ON DELETE SET NULL
        DB::statement('ALTER TABLE `subjects` ADD CONSTRAINT `fk_subject_teacher` FOREIGN KEY (`teacher_code`) REFERENCES `teacher`(`teacher_code`) ON DELETE SET NULL');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::statement('ALTER TABLE `subjects` DROP FOREIGN KEY `fk_subject_teacher`');

        // Revert to not null
        DB::statement('ALTER TABLE `subjects` MODIFY `teacher_code` varchar(64) NOT NULL');

        // Recreate original cascade behavior
        DB::statement('ALTER TABLE `subjects` ADD CONSTRAINT `fk_subject_teacher` FOREIGN KEY (`teacher_code`) REFERENCES `teacher`(`teacher_code`) ON DELETE CASCADE');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
