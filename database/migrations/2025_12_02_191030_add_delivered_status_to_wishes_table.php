<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE wishes MODIFY COLUMN status ENUM('pending', 'granted', 'denied', 'in_progress', 'delivered') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to the original enum values
        DB::statement("ALTER TABLE wishes MODIFY COLUMN status ENUM('pending', 'granted', 'denied', 'in_progress') DEFAULT 'pending'");
    }
};
