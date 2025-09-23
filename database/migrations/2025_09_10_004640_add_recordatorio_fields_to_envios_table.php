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
            $table->timestamp('tiempo_recordatorio')->nullable()->after('tiempo_expiracion');
            $table->boolean('recordatorio_enviado')->default(false)->after('tiempo_recordatorio');
            $table->timestamp('recordatorio_enviado_at')->nullable()->after('recordatorio_enviado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('envios', function (Blueprint $table) {
            $table->dropColumn(['tiempo_recordatorio', 'recordatorio_enviado', 'recordatorio_enviado_at']);
        });
    }
};
