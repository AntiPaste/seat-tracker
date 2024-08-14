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
        Schema::table('character_locations', function (Blueprint $table) {
            $table->datetime('last_modified')->nullable();
        });

        DB::statement('UPDATE character_locations SET last_modified = updated_at;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('character_locations', function (Blueprint $table) {
            $table->dropColumn('last_modified');
        });
    }
};
