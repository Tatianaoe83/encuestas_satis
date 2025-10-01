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
        Schema::table('envios', function (Blueprint $table) {
            // Agregar campo para calcular el promedio de la pregunta 1
            $table->decimal('promedio_respuesta_1', 3, 2)->nullable()->comment('Promedio de las 5 respuestas de pregunta 1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('envios', function (Blueprint $table) {
            $table->dropColumn('promedio_pregunta_1');
        });
    }
};
