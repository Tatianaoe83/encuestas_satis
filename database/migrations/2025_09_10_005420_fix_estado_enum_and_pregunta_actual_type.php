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
        // Agregar 'error' al enum de estado
        DB::statement("ALTER TABLE envios MODIFY COLUMN estado VARCHAR(20)");
        DB::statement("ALTER TABLE envios MODIFY COLUMN estado ENUM('pendiente', 'enviado', 'respondido', 'cancelado', 'en_proceso', 'completado', 'esperando_respuesta', 'error') DEFAULT 'pendiente'");
        
        // Cambiar pregunta_actual de decimal a string para permitir valores como 'encuesta'
        Schema::table('envios', function (Blueprint $table) {
            $table->string('pregunta_actual')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir estado enum
        DB::statement("ALTER TABLE envios MODIFY COLUMN estado VARCHAR(20)");
        DB::statement("ALTER TABLE envios MODIFY COLUMN estado ENUM('pendiente', 'enviado', 'respondido', 'cancelado', 'en_proceso', 'completado', 'esperando_respuesta') DEFAULT 'pendiente'");
        
        // Revertir pregunta_actual a decimal
        Schema::table('envios', function (Blueprint $table) {
            $table->decimal('pregunta_actual', 3, 1)->nullable()->change();
        });
    }
};
