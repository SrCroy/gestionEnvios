<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class paquetes extends Model
{
    protected $table = 'paquetes';
    protected $fillable = [
        'idDestinatario', 'idRemitente', 'idVehiculo', 'descripcion', 'peso', 'altura', 'fechaRegistro', 'fechaEstimadaEntrega', 'estadoActual'
    ];

    public function destinatario()
    {
        return $this->belongsTo(clientes::class, 'idDestinatario');
    }

    public function remitente()
    {
        return $this->belongsTo(clientes::class, 'idRemitente');
    }

    public function vehiculo()
    {
        return $this->belongsTo(vehiculo::class, 'idVehiculo');
    }
}
