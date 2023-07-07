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
        Schema::table('focus_sessions', function (Blueprint $table) {

            //
            $table->integer('session_length')->after('completed_at')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('focus_sessions', function (Blueprint $table) {
            //
            $table->dropColumn('session_length');
        });
    }
};
