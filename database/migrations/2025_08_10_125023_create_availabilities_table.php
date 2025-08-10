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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->integer('availability_count')->default(0);
            $table->unsignedSmallInteger('slot_key');
            $table->text('notes')->nullable();
            // vincoli utili
            $table->unique(['user_id', 'slot_key']); // un solo record per utente/slot
            $table->index('slot_key');               // per raggruppare/filtrare per slot
            //FK Student
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
