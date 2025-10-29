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
        // Use raw SQL to alter the column to allow NULL. Using a raw statement avoids requiring doctrine/dbal.
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `subjects` MODIFY `subject_description` TEXT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to NOT NULL (set empty string as default fallback)
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `subjects` MODIFY `subject_description` TEXT NOT NULL");
    }
};
