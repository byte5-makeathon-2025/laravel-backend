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
            $table->string('street')->after('name');
            $table->string('house_number')->after('street');
            $table->string('postal_code')->after('house_number');
            $table->string('city')->after('postal_code');
            $table->string('country')->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wishes', function (Blueprint $table) {
            $table->dropColumn(['street', 'house_number', 'postal_code', 'city', 'country']);
        });
    }
};
