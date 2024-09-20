<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_action')->nullable(); // Es. 'mark_absence' o 'rec_absence'
            $table->unsignedBigInteger('last_alias_id')->nullable(); // Memorizza l'ID dell'ultimo alias modificato
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_action');
            $table->dropColumn('last_alias_id');
        });
    }
};
