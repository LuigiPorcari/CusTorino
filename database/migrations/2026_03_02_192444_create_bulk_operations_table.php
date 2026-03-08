<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bulk_operations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // chi l'ha lanciata (opzionale)
            $table->string('type'); // es: purge_logs, reset_recuperi...
            $table->string('status')->default('queued'); // queued|running|completed|failed
            $table->text('message')->nullable(); // messaggio finale/errore
            $table->unsignedBigInteger('affected_rows')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulk_operations');
    }
};