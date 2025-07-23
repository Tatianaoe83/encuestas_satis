<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'cliente_id',
        'whatsapp_number',
        'twilio_message_sid',
        'twilio_conversation_sid',
        'whatsapp_message',
        'whatsapp_responses',
        'pregunta_1',
        'pregunta_2',
        'pregunta_3',
        'pregunta_4',
        'respuesta_1',
        'respuesta_2',
        'respuesta_3',
        'respuesta_4',
        'estado',
        'fecha_envio',
        'fecha_respuesta',
        'whatsapp_sent_at',
        'whatsapp_responded_at',
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
        'whatsapp_responses' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the cliente that owns the envio.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
} 