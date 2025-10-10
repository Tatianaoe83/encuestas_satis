<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Envio extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $table = 'envios';
    protected $primaryKey = 'idenvio';
 

    protected $fillable = [
        'cliente_id',
        'whatsapp_number',
        'twilio_message_sid',
        'content_sid',
        'contentSidRecordatorio',
        'contentSidVencimiento',
        'whatsapp_error',
        'whatsapp_sent_at',
        'whatsapp_responded_at',
        'esperando_respuesta_desde',
        'tiempo_espera_minutos',
        'tiempo_expiracion',
        'tiempo_recordatorio',
        'recordatorio_enviado',
        'recordatorio_enviado_at',
        'timer_activo',
        'estado_timer',
        'respuesta_2',
        'respuesta_3',
        'estado',
        'pregunta_actual',
        'fecha_envio',
        'fecha_respuesta',
        'respuesta_1_1',
        'respuesta_1_2',
        'respuesta_1_3',
        'respuesta_1_4',
        'respuesta_1_5',
        'promedio_respuesta_1'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_envio' => 'datetime',
        'fecha_respuesta' => 'datetime',
        'whatsapp_sent_at' => 'datetime',
        'whatsapp_responded_at' => 'datetime',
        'esperando_respuesta_desde' => 'datetime',
        'tiempo_expiracion' => 'datetime',
        'tiempo_recordatorio' => 'datetime',
        'recordatorio_enviado_at' => 'datetime',
        'timer_activo' => 'boolean',
        'recordatorio_enviado' => 'boolean',
        'whatsapp_responses' => 'array',
        'promedio_respuesta_1' => 'decimal:2',
        'respuesta_1_1' => 'integer',
        'respuesta_1_2' => 'integer',
        'respuesta_1_3' => 'integer',
        'respuesta_1_4' => 'integer',
        'respuesta_1_5' => 'integer',
        'pregunta_actual' => 'string',
        'respuesta_2' => 'string',
        'respuesta_3' => 'string',
    ];

    /**
     * Get the cliente that owns the envio.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'idcliente');
    }
} 