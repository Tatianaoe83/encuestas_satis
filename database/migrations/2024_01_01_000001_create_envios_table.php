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
        Schema::create('envios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->text('pregunta_1');
            $table->text('pregunta_2');
            $table->text('pregunta_3');
            $table->text('pregunta_4');
            $table->text('respuesta_1')->nullable();
            $table->text('respuesta_2')->nullable();
            $table->text('respuesta_3')->nullable();
            $table->text('respuesta_4')->nullable();
            $table->enum('estado', ['pendiente', 'enviado', 'respondido', 'cancelado'])->default('pendiente');
            $table->timestamp('fecha_envio')->nullable();
            $table->timestamp('fecha_respuesta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('envios');
    }
}; 