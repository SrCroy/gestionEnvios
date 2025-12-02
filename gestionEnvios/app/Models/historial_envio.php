<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class historial_envio extends Model
{
    protected $table = 'historial_envios';
    protected $fillable = [
        'idPaquete', 'idMotorista', 'estado', 'comentarios', 'fotoEvidencia', 'fechaCambio'
    ];

    public function paquete()
    {
        return $this->belongsTo(paquetes::class, 'idPaquete');
    }

    public function motorista()
    {
        return $this->belongsTo(User::class, 'idMotorista');
    }
}
