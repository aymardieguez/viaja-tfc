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
        'imagen',
        'favorito',
        'filtros_ia',
        "personas",
        "intereses"
    ];

    protected $casts = [
        'filtros_ia' => 'array',
        'favorito' => 'boolean',
        "intereses" => "array"
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
