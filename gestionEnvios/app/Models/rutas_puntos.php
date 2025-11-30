<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rutas_puntos extends Model
{
    protected $table = 'rutas_puntos';
    protected $fillable = [
        'idRuta', 'idPaquete', 'tipo', 'latitud', 'longitud', 'orden'
    ];

    public function ruta()
    {
        return $this->belongsTo(rutas::class, 'idRuta');
    }

    public function paquete()
    {
        return $this->belongsTo(paquetes::class, 'idPaquete');
    }
}
