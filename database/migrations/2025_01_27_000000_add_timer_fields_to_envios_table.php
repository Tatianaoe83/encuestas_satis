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
            $table->string('content_sid')->nullable()->after('cliente_id');
            $table->timestamp('esperando_respuesta_desde')->nullable()->after('content_sid');
            $table->integer('tiempo_espera_minutos')->default(30)->after('esperando_respuesta_desde');
            $table->timestamp('tiempo_expiracion')->nullable()->after('tiempo_espera_minutos');
            $table->boolean('timer_activo')->default(false)->after('tiempo_expiracion');
            $table->string('estado_timer')->default('inactivo')->after('timer_activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('envios', function (Blueprint $table) {
            $table->dropColumn([
                'content_sid',
                'esperando_respuesta_desde',
                'tiempo_espera_minutos',
                'tiempo_expiracion',
                'timer_activo',
                'estado_timer'
            ]);
        });
    }
};
