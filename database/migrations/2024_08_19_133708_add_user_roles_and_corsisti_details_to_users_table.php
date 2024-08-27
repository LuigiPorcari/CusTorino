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
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_trainer')->default(false);
            $table->boolean('is_corsista')->default(false);
            $table->string('name');
            $table->string('cognome');

            // Campi per i corsisti
            $table->string('livello')->nullable();
            $table->string('genere')->nullable();
            $table->boolean('cus_card')->default(0);
            $table->boolean('visita_medica')->default(0);
            $table->boolean('pagamento')->default(0);
            $table->boolean('universitario')->default(0);
            $table->integer('NrecuperiTemp')->default(0);
            $table->integer('Nrecuperi')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rimuovi i campi aggiunti per i ruoli
            $table->dropColumn('is_admin');
            $table->dropColumn('is_trainer');
            $table->dropColumn('is_corsista');
            $table->dropColumn('name');
            $table->dropColumn('cognome');

            // Rimuovi i campi specifici per i corsisti
            $table->dropColumn('livello');
            $table->dropColumn('genere');
            $table->dropColumn('cus_card');
            $table->dropColumn('visita_medica');
            $table->dropColumn('pagamento');
            $table->dropColumn('universitario');
            $table->dropColumn('NrecuperiTemp');
            $table->dropColumn('Nrecuperi');
        });
    }
};
