<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRespuesta extends Model
{
    use HasFactory;

    protected $table = 'chat_respuestas';

    protected $fillable = [
        'message_sid',
        'from_number',
        'to_number',
        'body',
        'status',
        'twilio_data'
    ];

    protected $casts = [
        'twilio_data' => 'array'
    ];

    /**
     * Obtener respuestas por número de teléfono
     */
    public function scopeFromNumber($query, $number)
    {
        return $query->where('from_number', $number);
    }

    /**
     * Obtener respuestas recientes
     */
    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    /**
     * Formatear número para mostrar
     */
    public function getFormattedFromNumberAttribute()
    {
        return str_replace('whatsapp:', '', $this->from_number);
    }

    /**
     * Formatear fecha para mostrar
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }
}
