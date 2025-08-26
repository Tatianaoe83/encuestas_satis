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
        Schema::create('chat_respuestas', function (Blueprint $table) {
            $table->id();
            $table->string('message_sid')->unique(); // SID único del mensaje de Twilio
            $table->string('from_number'); // Número que envía la respuesta
            $table->string('to_number'); // Número que recibe la respuesta
            $table->text('body'); // Contenido del mensaje
            $table->string('status')->default('received'); // Estado del mensaje
            $table->json('twilio_data')->nullable(); // Datos completos de Twilio
            $table->timestamps();
            
            // Índices para búsquedas eficientes
            $table->index('from_number');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_respuestas');
    }
};
