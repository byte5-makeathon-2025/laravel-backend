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
        Schema::table('wishes', function (Blueprint $table) {
            $table->text('bribe_offer')->nullable()->after('product_price');
            $table->enum('bribe_status', ['pending', 'accepted', 'rejected'])->nullable()->after('bribe_offer');
            $table->timestamp('bribe_submitted_at')->nullable()->after('bribe_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wishes', function (Blueprint $table) {
            $table->dropColumn(['bribe_offer', 'bribe_status', 'bribe_submitted_at']);
        });
    }
};
