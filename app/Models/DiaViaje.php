<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaViaje extends Model
{
    protected $table = 'dias_viaje';

    protected $fillable = [
        'viaje_id',
        'numero_dia',
        'titulo',
        'descripcion'
    ];

    public function viaje()
    {
        return $this->belongsTo(Viaje::class);
    }
}
