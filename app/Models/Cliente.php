<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $table = 'clientes';
    protected $primaryKey = 'idcliente';
   


    protected $fillable = [
        'asesor_comercial',
        'razon_social',
        'nombre_completo',
        'puesto',
        'celular',
        'correo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the envios for the cliente.
     */
    public function envios()
    {
        return $this->hasMany(Envio::class, 'cliente_id', 'idcliente');
    }
} 