<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero necesitamos eliminar la restricción del enum actual
        DB::statement("ALTER TABLE envios MODIFY COLUMN estado VARCHAR(20)");
        
        // Luego agregamos la nueva restricción del enum con todos los valores necesarios incluyendo 'esperando_respuesta'
        DB::statement("ALTER TABLE envios MODIFY COLUMN estado ENUM('pendiente', 'enviado', 'respondido', 'cancelado', 'en_proceso', 'completado', 'esperando_respuesta') DEFAULT 'pendiente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a los valores anteriores sin 'esperando_respuesta'
        DB::statement("ALTER TABLE envios MODIFY COLUMN estado ENUM('pendiente', 'enviado', 'respondido', 'cancelado', 'en_proceso', 'completado') DEFAULT 'pendiente'");
    }
};
