<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class asignaciones extends Model
{
    protected $table = 'asignaciones';
    protected $fillable = [
        'idPaquete', 'idMotorista', 'idVehiculo', 'fechaAsignacion'
    ];

    public function paquete()
    {
        return $this->belongsTo(paquetes::class, 'idPaquete');
    }

    public function motorista()
    {
        return $this->belongsTo(User::class, 'idMotorista');
    }

    public function vehiculo()
    {
        return $this->belongsTo(vehiculo::class, 'idVehiculo');
    }
}
