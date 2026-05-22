<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Viaje extends Model
{
    protected $fillable = [
        'user_id',
        'titulo',
        'destino',
        'presupuesto',
        'noches',
        'imagenes',
        'favorito',
        'filtros_ia',
        'personas',
        'intereses',
        'mes',
        'rango_edad',
        'modo_pro',
        'favorito',
        'valoracion',
    ];

    protected $casts = [
        'filtros_ia' => 'array',
        'favorito' => 'boolean',
        'intereses' => 'array',
        'imagenes' => 'array',
        'favorito' => 'boolean',
        'valoracion' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dias()
    {
        return $this->hasMany(DiaViaje::class);
    }
}
