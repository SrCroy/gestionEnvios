<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rutas extends Model
{
    protected $table = 'rutas';
    protected $fillable = [
        'idMotorista', 'fecha', 'estado'
    ];

    public function motorista()
    {
        return $this->belongsTo(User::class, 'idMotorista');
    }

    public function puntos()
    {
        return $this->hasMany(rutas_puntos::class, 'idRuta');
    }
}
