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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Utente che ha eseguito l'operazione
            $table->string('action'); // Tipo di operazione: create, update, delete
            $table->unsignedBigInteger('alias_id')->nullable(); // ID di Alias, se applicabile
            $table->unsignedBigInteger('group_id')->nullable(); // ID di Group, se applicabile
            $table->unsignedBigInteger('user_modified_id')->nullable(); // ID di User modificato, se applicabile
            $table->string('model_name')->nullable(); // Nome dell'elemento
            $table->string('model_cognome')->nullable(); // Cognome dell'elemento
            $table->string('model_type')->nullable(); // Tipo dell'elemento
            $table->date('data_allenamento')->nullable(); //Data Alias
            $table->json('changes')->nullable(); // Dettagli delle modifiche
            $table->string('custom_action')->nullable(); // Tipo di operazione specifico
            $table->boolean('is_corsista')->default(0); // Aggiunge la colonna is_corsista
            $table->boolean('is_admin')->default(0); // Aggiunge la colonna is_admin
            $table->boolean('is_trainer')->default(0); // Aggiunge la colonna is_trainer
            $table->string('user_name')->nullable(); // Aggiunge la colonna user_name
            $table->string('user_cognome')->nullable();
            $table->timestamps();

            // Definisci le chiavi esterne
            $table->foreign('alias_id')->references('id')->on('aliases')->onDelete('set null');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
            $table->foreign('user_modified_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
