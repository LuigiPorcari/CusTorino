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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('giorno_settimana');
            $table->time('orario');
            $table->integer('campo');
            $table->string('tipo');
            $table->string('condiviso');
            $table->integer('numero_massimo_partecipanti');
            $table->integer('livello');
            $table->json('studenti_id')->nullable();
            $table->date('data_inizio_corso');
            $table->date('data_fine_corso');
            $table->unsignedBigInteger('primo_allenatore_id')->nullable();
            $table->foreign('primo_allenatore_id')->references('id')->on('users');
            $table->unsignedBigInteger('secondo_allenatore_id')->nullable();
            $table->foreign('secondo_allenatore_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
